<?php/*** Controllers-specific form** @package NocturnalCore*/class CFormControllers extends CForm implements ArrayAccess{	public function __construct($form = array(), $elements = array())	{		parent::__construct($form, $elements);	}		public function GetHTMLForElements() {    $html = '<table><tr>';	$even = 0;    foreach($this->elements as $element) {	  if (isset($element))	  {		if ($even==3)		{			$html .= '</tr><tr>';			$even = 0;		}		$html .= '<td>'.$element->GetHTML().'</td>';		$even++;	  }    }	$html .= '</tr></table>';    return $html;  }}