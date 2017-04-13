<?php

/**
 * Created by PhpStorm.
 * User: jaronwhite
 * Date: 2/19/2017
 * Time: 9:09 PM
 */
class as_slider {
	public $slider_id, $slider_name, $date_created, $size, $speed, $b_controls, $lr_controls, $slides;

	public function __construct() {
		$this->slider_id    = "";
		$this->slider_name  = "";
		$this->date_created = "";
		$this->size         = "full";
		$this->speed        = "6000";
		$this->b_controls   = 1;
		$this->lr_control   = 0;
		$this->slides       = [ ];
	}

	/**
	 * @return string
	 */
	public function getSliderId() {
		return $this->slider_id;
	}

	/**
	 * @param string $slider_id
	 */
	public function setSliderId( $slider_id ) {
		$this->slider_id = $slider_id;
	}

	/**
	 * @return string
	 */
	public function getSliderName() {
		return $this->slider_name;
	}

	/**
	 * @param string $slider_name
	 */
	public function setSliderName( $slider_name ) {
		$this->slider_name = $slider_name;
	}

	/**
	 * @return string
	 */
	public function getDateCreated() {
		return $this->date_created;
	}

	/**
	 * @param string $date_created
	 */
	public function setDateCreated( $date_created ) {
		$this->date_created = $date_created;
	}

	/**
	 * @return string
	 */
	public function getSize() {
		return $this->size;
	}

	/**
	 * @param string $size
	 */
	public function setSize( $size ) {
		$this->size = $size;
	}

	/**
	 * @return string
	 */
	public function getSpeed() {
		return $this->speed;
	}

	/**
	 * @param string $speed
	 */
	public function setSpeed( $speed ) {
		$this->speed = $speed;
	}

	/**
	 * @return string
	 */
	public function getPlacementControls() {
		return $this->placement_controls;
	}

	/**
	 * @param string $placement_controls
	 */
	public function setPlacementControls( $placement_controls ) {
		$this->placement_controls = $placement_controls;
	}

	/**
	 * @return mixed
	 */
	public function getLrControls() {
		return $this->lr_controls;
	}

	/**
	 * @param mixed $lr_controls
	 */
	public function setLrControls( $lr_controls ) {
		$this->lr_controls = $lr_controls;
	}

	/**
	 * @return array
	 */
	public function getSlides() {
		return $this->slides;
	}

	/**
	 * @param array $slides
	 */
	public function setSlides( $slides ) {
		$this->slides = $slides;
	}

}