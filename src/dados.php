<?php
function obterPerfisExemplo(): array
{
    return [
        'Jean Koerich'  => 'aluno',
        'Ricardo Alves' => 'professor',
        'Beatriz Lima'  => 'aluno',
        'Mariana Torres'=> 'professor',
    ];
}

function obterEmprestimosExemplo(): array
{
    return [
        ['userId' => 'Jean Koerich',  'itemId' => 'livro_A', 'checkoutDate' => '2025-10-01', 'returnDate' => '2025-10-10'],
        ['userId' => 'Jean Koerich',  'itemId' => 'livro_B', 'checkoutDate' => '2025-10-01', 'returnDate' => '2025-10-13'],
        ['userId' => 'Ricardo Alves', 'itemId' => 'livro_C', 'checkoutDate' => '2025-09-01', 'returnDate' => '2025-10-03'],
        ['userId' => 'Ricardo Alves', 'itemId' => 'livro_D', 'checkoutDate' => '2025-09-01', 'returnDate' => '2025-10-08'],
        ['userId' => 'Beatriz Lima',  'itemId' => 'livro_E', 'checkoutDate' => '2025-10-20', 'returnDate' => null],
        ['userId' => 'Beatriz Lima',  'itemId' => 'livro_F', 'checkoutDate' => '2025-10-11', 'returnDate' => null],
        ['userId' => 'Mariana Torres','itemId' => 'livro_G'],
    ];
}
