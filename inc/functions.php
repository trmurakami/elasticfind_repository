<?php
/**
 * PHP version 7
 * File: Functions
 *
 * @category Functions
 * @package  Functions
 * @author   Tiago Rodrigo Marçal Murakami <tiago.murakami@dt.sibi.usp.br>
 */
if (file_exists('uspfind_core/uspfind_core.php')) {
    include 'uspfind_core/uspfind_core.php';
} elseif (file_exists('../uspfind_core/uspfind_core.php')) {
    include '../uspfind_core/uspfind_core.php';
} else {
    include '../../uspfind_core/uspfind_core.php';
}