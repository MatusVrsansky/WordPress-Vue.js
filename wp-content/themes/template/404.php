<?php
/**
 * The template for displaying 404 pages (Not Found)
 */

$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/404.twig', $context);
