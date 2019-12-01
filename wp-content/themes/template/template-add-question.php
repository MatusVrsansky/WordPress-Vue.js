<?php

/**
 * Template Name: Add Question Form
 */

$context = Timber\Timber::get_context();
$context['post'] = new Timber\Post();

Timber\Timber::render('page/add-question.twig', $context);
