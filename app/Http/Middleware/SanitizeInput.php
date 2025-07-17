<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class SanitizeInput
{
    /**
     * Lista de tags HTML permitidas
     */
    protected array $allowedTags = [
        'p', 'br', 'strong', 'em', 'ul', 'ol', 'li', 'a',
        'h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'blockquote', 'code'
    ];

    /**
     * Lista de atributos permitidos por tag
     */
    protected array $allowedAttributes = [
        'a' => ['href', 'title', 'target', 'rel'],
        'img' => ['src', 'alt', 'title', 'width', 'height'],
        'code' => ['class'],
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next): Response
    {
        $input = $request->all();

        array_walk_recursive($input, function (&$value, $key) {
            if ($key === 'email') {
                return;
            }
            if (is_string($value)) {
                $value = $this->sanitizeValue($value);
            }
        });

        $request->merge($input);

        return $next($request);
    }

    /**
     * Sanitiza um valor de entrada
     *
     * @param string $value
     * @return string
     */
    protected function sanitizeValue(string $value): string
    {
        // Remove caracteres invisíveis e normalize espaços em branco
        $value = trim(preg_replace('/\s+/', ' ', $value));

        // Remove caracteres NULL e outros caracteres de controle
        $value = preg_replace('/[\x00-\x1F\x7F]/u', '', $value);

        // Remove scripts potencialmente maliciosos
        $value = $this->removeScripts($value);

        // Sanitiza URLs
        $value = $this->sanitizeUrls($value);

        // Sanitiza HTML permitido
        $value = $this->sanitizeHtml($value);

        // Converte caracteres especiais em entidades HTML
        $value = htmlspecialchars($value, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return $value;
    }

    /**
     * Remove scripts potencialmente maliciosos
     *
     * @param string $value
     * @return string
     */
    protected function removeScripts(string $value): string
    {
        // Remove tags script
        $value = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $value);

        // Remove eventos inline
        $value = preg_replace('/on\w+="[^"]*"/', '', $value);
        $value = preg_replace('/on\w+=\'[^\']*\'/', '', $value);

        // Remove javascript: URLs
        $value = preg_replace('/javascript:[^\'"]*/', '', $value);

        return $value;
    }

    /**
     * Sanitiza URLs
     *
     * @param string $value
     * @return string
     */
    protected function sanitizeUrls(string $value): string
    {
        // Sanitiza URLs em atributos href
        $value = preg_replace_callback('/href=["\'](.*?)["\']/', function ($matches) {
            $url = $matches[1];
            if (filter_var($url, FILTER_VALIDATE_URL)) {
                return 'href="' . $url . '"';
            }
            return 'href="#"';
        }, $value);

        return $value;
    }

    /**
     * Sanitiza HTML permitido
     *
     * @param string $value
     * @return string
     */
    protected function sanitizeHtml(string $value): string
    {
        // Remove todas as tags HTML exceto as permitidas
        $value = strip_tags($value, '<' . implode('><', $this->allowedTags) . '>');

        // Remove atributos não permitidos
        foreach ($this->allowedAttributes as $tag => $attributes) {
            $value = preg_replace_callback(
                "/<$tag\b[^>]*>/i",
                function ($matches) use ($attributes) {
                    $tag = $matches[0];
                    foreach ($attributes as $attr) {
                        if (preg_match("/\b$attr\s*=\s*[\"'][^\"']*[\"']/i", $tag)) {
                            continue;
                        }
                        $tag = preg_replace("/\b$attr\s*=\s*[\"'][^\"']*[\"']/i", '', $tag);
                    }
                    return $tag;
                },
                $value
            );
        }

        return $value;
    }
}
