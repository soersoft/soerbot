<?php

$directories = ['src', 'tests', 'commands'];

$rules = [
    '@PSR1' => true,
    '@PSR2' => true,

    // Laravel Style-CI
    'align_multiline_comment' => ['comment_type' => 'phpdocs_like'], // Каждая строчка много строчного phpdoc коментария должна начинаться со * и быть выравнена по первой строке [PSR-5]
    'binary_operator_spaces' => true, // Бинарные операторы ( == , > , < ) должны быть окружены одним пробелом
    'blank_line_after_opening_tag' => true, // Пустая строка после открывающего тега
    'blank_line_before_statement' => true, // Пустая строка перед возвратом ('break', 'continue', 'declare', 'return', 'throw', 'try')
    'concat_space' => ['spacing' => 'one'], // Символ конкатинации строк не имеет пробелов вокруг себя
    'function_typehint_space' => true, // Пробел между типом переменной и названием в праметрах функции
    'single_line_comment_style' => true, // Однострочные комментарии имеющие в начале # или /*  */ заменяются на //
    'include' => true, // Include/Require и путь к файлу должны быть разделены одним пробелом.
    'ordered_imports' => ['sortAlgorithm' => 'length'], // Сортировка импортов по длинне (опционально по алфавиту)
    'lowercase_cast' => true, // (int) $number - приведение типов должно быть в нижнем регистре
    'magic_constant_casing' => true, // Магические константы должны быть представлены в верхнем регистре. (__CLASS__)
    'method_separation' => true, // методы должны быть отделены Одной пустой строкой друг от друга (DEPRECATED: use class_attributes_separation instead. в версии 3)
    'native_function_casing' => true, // стандартные функции должны использовать нижний регистр
    'no_blank_lines_after_class_opening' => true, // Не должно быть пустой строки после открывающей скобки класса
    'no_blank_lines_after_phpdoc' => true, // Не должно быть пустых строк между блоком комментариев и самим кодом
    'no_empty_phpdoc' => true, // Не должно быть пустых блоков с комментариями
    'no_empty_statement' => true, // Убирает лишнюю ; на пустой строке - или дублирующую в строке
    'no_extra_consecutive_blank_lines' => true, // Убирает лишние пустые строки (несколько пустых строк приводятся к одной) (DEPRECATED: use no_extra_blank_lines instead. в версии 3)
    'no_leading_import_slash' => true, // Убирает ведущий слеш в импортах
    'no_leading_namespace_whitespace' => true, // Перед объявление namespace не должно быть пробелов
    'no_multiline_whitespace_around_double_arrow' => true, // Не должно быть переносов строк при использовании =>
    'no_multiline_whitespace_before_semicolons' => true, // Не должно быть переносов строк перед закрывающей ; ( DEPRECATED: use multiline_whitespace_before_semicolons instead.  в версии 3)
    'no_short_bool_cast' => true, // Не использовать каст к булеву используя двойное отрицание !!
    'no_singleline_whitespace_before_semicolons' => true,  // Не должно быть пробелов перед закрывающей ;
    'no_spaces_around_offset' => true, // Не должно быть пробелов внутри квадратных скобок при указании индекса массива // $array[ 0 ];
    'no_trailing_comma_in_list_call' => true, // Удаляет последнюю висячую запятую в списках вызов функций
    'no_trailing_comma_in_singleline_array' => true, // Удаляет последнюю висячую запятую в однострочном массиве
    'no_unneeded_control_parentheses' => true, // Убирает () после управляющие операторов break(), continue() etc
    'no_unused_imports' => true, // Не используемые импорты будут удалены
    'no_useless_return' => true, // В конце функции не должно быть пустого значения return
    'no_whitespace_before_comma_in_array' => true, // Не должно быть пробела перед запятой в перечислении элементов массива
    'no_whitespace_in_blank_line' => true, // Удаляет пустые символы в пустой строке
    'normalize_index_brace' => true, // При обращении к элементам массива по индексу должно использовать квадратные скобки
    'object_operator_without_whitespace' => true, // вызовы методов объекта -> не должны отделяться пробелами
    'no_mixed_echo_print' => true, // Заменяет print на echo
    'array_syntax' => ['syntax' => 'short'], // Приводит массивы к краткой форме []
    'short_scalar_cast' => true, // Cast (boolean) и (integer) должны быть записаны как (bool) и (int), (double) и (real) как (float).
    'single_blank_line_before_namespace' => true, // Перед объявлением пространства имен должно быть ровно одна пустая строка.
    'single_quote' => true, // Преобразование двойных кавычек в одинарные кавычки для простых строк.
    'space_after_semicolon' => true, // Не должно быть пробелов после закрывающей ;
    'standardize_not_equals' => true, // Заменяет <> на != в качестве оператора сравнения НЕ_РАВНО
    'ternary_operator_spaces' => true, // Отсупы вокруг тренарных операторов
    'trailing_comma_in_multiline_array' => true, // Многострочные массивы должны иметь запятую после последнего элемента
    'trim_array_spaces' => true, // Убирает пробелы вокруг элементов внутри массивов
    'unary_operator_spaces' => true, // Унарные операторы должня располагаться рядом с элементами к которым относятся
    'whitespace_after_comma_in_array' => true, // Должен быть пробел после запятой после каждого элемента в однострочном массиве

    // Рекомендованные Style-CI - не входящие в Laravel комплект
    'new_with_braces' => true, // Инстансы классов создаваемые с помощью new должны содержать скобки в конце: new ClassName();
    'phpdoc_order' => true, // В phpdoc сначала должны идти @params затем @throws и в конце @return

    // phpdoc
    'phpdoc_indent' => true, // Докблок должен быть выравнен в соответсвии с кодом к которому относится
    'phpdoc_no_access' => true, // Убирает @access атрибут из phpdoc
    'phpdoc_scalar' => true, // Скалярные типы всегда должны быть написаны в одной и той же форме. int not integer, bool not boolean, float не real или double.
    'phpdoc_single_line_var_spacing' => true, // Отдельная строка @var PHPDoc должна иметь правильный интервал.
    'phpdoc_summary' => true, // Коментарий phpdoc должен заканчиваться точкой, восклицательным знаком, или вопросительным знаком
    'phpdoc_to_comment' => true, // Докблоки должны использоваться только на структурных элементах.
    'phpdoc_trim' => true, // Phpdocs должен начинаться и заканчиваться содержимым, исключая самую первую и последнюю строку docblocks.
    'phpdoc_no_alias_tag' => true, // @type всегда должен быть записан как @var.
    'phpdoc_types' => true, // php типы должны использоваться в правильном регистре int а не INT
    'phpdoc_var_without_name' => true, // Аннотации @var и @type не должны содержать имя переменной.
];

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(false)
    ->setRules($rules)
    ->setCacheFile(__DIR__.'/vendor/.php_cs.cache')
    ->setFinder(
        PhpCsFixer\Finder::create()
            ->in($directories)
    );
