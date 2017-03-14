<?php

namespace Statamic\Addons\WrapClasses;

use Statamic\Extend\Modifier;

class WrapClassesModifier extends Modifier
{
	/**
	 * Wraps an HTML tag (with classes) around the value
	 *
	 * @param $value
	 * @return string
	 */
	public function index($value, $params)
	{
	    $classes = explode( '.', array_get($params, 0) );

	    $tag = array_shift( $classes );

	    if( count( $classes ) ) {
	      $class = ' class="' . implode( ' ', $classes ) . '"';
	    } else {
	      $class = '';
	    }

	    return "<{$tag}{$class}>{$value}</{$tag}>";
	}

}
