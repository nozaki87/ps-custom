<?php
/**
 * This file implements the class Image.
 * 
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright 2011 Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

/**
 * Image
 *
 * The image is displayed in the ImagePanel. This file
 * implements its displaying.
 *
 * @category  Website
 * @package   Photoshow
 * @author    Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright Thibaud Rohmer
 * @license   http://www.gnu.org/licenses/
 * @link      http://github.com/thibaud-rohmer/PhotoShow
 */

class Image implements HTMLObject
{
	/// URLencoded version of the relative path to file
	static public $fileweb;
	
	/// URLencoded version of the relative path to directory containing file
	private $dir;
	
	/// Width of the image
	private $x;
	
	/// Height of the image
	private $y;

	/// Force big image or not
	private $t;
	
	/// Viewmode
	private $viewmode;
	
	/**
	 * Create image
	 *
	 * @param string $file 
	 * @author Thibaud Rohmer
	 */
	public function __construct($file=NULL,$forcebig = false, $viewmode = 0){
		
		/// Check file type
		if(!isset($file) || !File::Type($file) || File::Type($file) != "Image")
			return;
		
		/// Set relative path (url encoded)
		$this->fileweb	=	urlencode(File::a2r($file));
		
		/// Set relative path to parent dir (url encoded)
		$this->dir	=	urlencode(dirname(File::a2r($file)));
		
		/// Get image dimensions
		list($this->x,$this->y)=getimagesize($file);

		/// Set big image
		if($forcebig){
			$this->t = "Big";
		}else{
			$this->t = "Img";
		}

		/// Auto viewmode setting
		if($viewmode == 0) {
			$exif = new Exif($file);
			$exifinfo = $exif->getEXIF();
			$model = $exifinfo['Model'];
			if(preg_match("/RICOH THETA.+/i",$model)) {
				$this->viewmode = 2;
				$this->t = "Big";
			} else {
				$this->viewmode = 1;
			}
		} else {
			$this->viewmode = $viewmode;
		}
	}
	
	
	/**
	 * Display the image on the website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		if ($this->viewmode == 2) {
			echo 	"<div id='image_big' ";
			echo 	"style='";
//			echo 		" max-width:".$this->x."px;";
//			echo 		" background: black url(\"?t=".$this->t."&f=$this->fileweb\") no-repeat center center;";
//			echo 		" background-size: contain;";
//			echo 		" -moz-background-size: contain;";
			echo 		" height:100%;";
			echo 	"';>";
			echo	"<canvas id='iota_canvas' data-theta-img='?t=".$this->t."&f=".$this->fileweb."' data-iota-fisheye='true' ></canvas>";
			echo	"</div>";
			echo	"<script src='src/js/iota-noz.jsx.js'></script>\n"; 
			return;
		}
		echo 	"<div id='image_big' ";
		echo 	"style='";
		echo 		" max-width:".$this->x."px;";
		echo 		" background: black url(\"?t=".$this->t."&f=$this->fileweb\") no-repeat center center;";
		echo 		" background-size: contain;";
		echo 		" -moz-background-size: contain;";
		echo 		" height:100%;";
		echo 	"';>";

		echo 	"<a href='?f=$this->dir'>"; 
		echo 	"<img src='inc/img.png' height='100%' width='100%' style='opacity:0;'>";
		echo 	"</a>";
		echo	"</div>";
	}
}

?>
