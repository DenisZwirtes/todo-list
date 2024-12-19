document.addEventListener('DOMContentLoaded', function () {
    const createCategoryLink = document.getElementById('create-category-link');

    if (createCategoryLink) {
        createCategoryLink.addEventListener('click', function (event) {
            event.preventDefault();

            Swal.fire({
                title: "Digite o nome da nova categoria",
                input: 'text',
                inputPlaceholder: "Nome da categoria",
                showCancelButton: true,
                confirmButtonText: "Criar",
                cancelButtonText: "Cancelar",
                preConfirm: (categoryName) => {
                    if (!categoryName) {
                        Swal.showValidationMessage("O nome da categoria é obrigatório!");
                    }
                    return categoryName;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const categoryName = result.value;

                    fetch('/categories/create-ajax', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        },
                        body: JSON.stringify({ name: categoryName }),
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: "Categoria criada com sucesso!",
                                    confirmButtonText: "OK"
                                }).then(() => {
                                    location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: "Erro",
                                    text: "Erro ao criar a categoria. Tente novamente.",
                                });
                            }
                        })
                        .catch(error => {
                            console.error("Erro:", error);
                            Swal.fire({
                                icon: 'error',
                                title: "Erro inesperado",
                                text: "Verifique o console para mais detalhes.",
                            });
                        });
                }
            });
        });
    }
});
