<?php

/**
 * Template Name: Homepage
 */


$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/default.twig', $context);

?>
