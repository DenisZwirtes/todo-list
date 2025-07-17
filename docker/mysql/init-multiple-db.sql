CREATE DATABASE IF NOT EXISTS todo_list;
CREATE DATABASE IF NOT EXISTS todo_list_test;
GRANT ALL PRIVILEGES ON todo_list.* TO 'todo_user'@'%';
GRANT ALL PRIVILEGES ON todo_list_test.* TO 'todo_user'@'%';
FLUSH PRIVILEGES;
