<div class="col">
<?php

# Выбрать дизайн открыток
# Вход: (array) $args
# Файл подключается через WP-функцию get_template_part()

echo design\list_postcards($args['id_term']);

?>
</div>
	