<?php
/**
 * Template Name: Highscore
 */

$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/highscore.twig', $context);
