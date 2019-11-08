<?php

/**
 * Template Name: Tic-Tac-Toe
 */

$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/tic-tac-toe.twig', $context);


