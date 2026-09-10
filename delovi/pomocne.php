<?php
function h($tekst)
{
    return htmlspecialchars($tekst ?? '', ENT_QUOTES, 'UTF-8');
}
