<?php

use \Wikia\Logger\WikiaLogger;

/**
 * Base class for the output of file transformation methods.
 *
 * @file
 * @ingroup Media
 */

/**
 * Base class for the output of MediaHandler::doTransform() and File::transform().
 *
 * @ingroup Media
 */
abstract class MediaTransformOutput {
	/**
	 * @var File
	 */
	var $file;

	var $width, $height, $url, $page, $path;
	protected $storagePath = false;

	/**
	 * Get the width of the output box
	 */
	public function getWidth() {
		return $this->width;
	}

	/**
	 * Get the height of the output box
	 */
	public function getHeight() {
		return $this->height;
	}

	/**
	 * @return string The thumbnail URL
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Set the URL for thumb object
	 * @param string $url
	 */
	public function setUrl( $url ) {
		$this->url = $url;
	}

	/**
	 * @return string|false The permanent thumbnail storage path
	 */
	public function getStoragePath() {
		return $this->storagePath;
	}

	/**
	 * @param $storagePath string The permanent storage path
	 * @return void
	 */
	public function setStoragePath( $storagePath ) {
		$this->storagePath = $storagePath;
	}

	/**
	 * Wikia change start
	 * @author Garth
	 */
	function mediaType() {
		return 'unknown';
	}

	/**
	 * For any class that doesn't override renderView, fallback to the old toHtml behavior
	 * @param array $options
	 * @return string
	 */
	public function renderView( $options = array() ) {
		return $this->toHtml( $options );
	}
	/**
	 * Wikia change end
	 */

	/**
	 * Fetch HTML for this transform output
	 *
	 * @param $options array Associative array of options. Boolean options
	 *     should be indicated with a value of true for true, and false or
	 *     absent for false.
	 *
	 *     alt          Alternate text or caption
	 *     desc-link    Boolean, show a description link
	 *     file-link    Boolean, show a file download link
	 *     custom-url-link    Custom URL to link to
	 *     custom-title-link  Custom Title object to link to
	 *     valign       vertical-align property, if the output is an inline element
	 *     img-class    Class applied to the <img> tag, if there is such a tag
	 *
	 * For images, desc-link and file-link are implemented as a click-through. For
	 * sounds and videos, they may be displayed in other ways.
	 *
	 * @return string
	 */
	abstract public function toHtml( $options = array() );

	/**
	 * This will be overridden to return true in error classes
	 */
	public function isError() {
		return false;
	}

	/**
	 * Check if an output thumbnail file actually exists.
	 * This will return false if there was an error, the
	 * thumbnail is to be handled client-side only, or if
	 * transformation was deferred via TRANSFORM_LATER.
	 *
	 * @return Bool
	 */
	public function hasFile() {
		// If TRANSFORM_LATER, $this->path will be false.
		// Note: a null path means "use the source file".
		return ( !$this->isError() && ( $this->path || $this->path === null ) );
	}

	/**
	 * Check if the output thumbnail is the same as the source.
	 * This can occur if the requested width was bigger than the source.
	 *
	 * @return Bool
	 */
	public function fileIsSource() {
		return ( !$this->isError() && $this->path === null );
	}

	/**
	 * Get the path of a file system copy of the thumbnail.
	 * Callers should never write to this path.
	 *
	 * @return string|false Returns false if there isn't one
	 */
	public function getLocalCopyPath() {
		if ( $this->isError() ) {
			return false;
		} elseif ( $this->path === null ) {
			return $this->file->getLocalRefPath();
		} else {
			return $this->path; // may return false
		}
	}

	/**
	 * Stream the file if there were no errors
	 *
	 * @param $headers Array Additional HTTP headers to send on success
	 * @return Bool success
	 */
	public function streamFile( $headers = array() ) {
		return $this->path && StreamFile::stream( $this->getLocalCopyPath(), $headers );
	}

	/**
	 * Wrap some XHTML text in an anchor tag with the given attributes
	 *
	 * @param $linkAttribs array
	 * @param $contents string
	 *
	 * @return string
	 */
	protected function linkWrap( $linkAttribs, $contents ) {
		if ( $linkAttribs ) {
			return Xml::tags( 'a', $linkAttribs, $contents );
		} else {
			return $contents;
		}
	}

	/**
	 * @param $title string
	 * @param string $params
	 * @return array
	 */
	public function getDescLinkAttribs( $title = null, $params = '' ) {
		$query = $this->page ? ( 'page=' . urlencode( $this->page ) ) : '';
		if ( $params ) {
			$query .= $query ? '&'.$params : $params;
		}
		$attribs = array(
			'href' => $this->file->getTitle()->getLocalURL( $query ),
			'class' => 'image',
		);
		if ( $title ) {
			$attribs['title'] = $title;
		}
		return $attribs;
	}
}

/**
 * Media transform output for images
 *
 * @ingroup Media
 */
class ThumbnailImage extends MediaTransformOutput {
	/**
	 * Get a thumbnail object from a file and parameters.
	 * If $path is set to null, the output file is treated as a source copy.
	 * If $path is set to false, no output file will be created.
	 *
	 * @param File $file File object
	 * @param string $url URL path to the thumb
	 * @param int $width File's width
	 * @param int $height File's height
	 * @param string|bool|null $path Filesystem path to the thumb
	 * @param int|bool $page Page number, for multi-page files
	 */
	function __construct( $file, $url, $width, $height, $path = false, $page = false ) {
		$this->file = $file;
		$this->url = $url;
		# start wikia change
		$timestamp = !empty( $file ) ? $file->getTimestamp() : false;
		$this->url = wfReplaceImageServer( $this->url, $timestamp );
		# end wikia change

		# These should be integers when they get here.
		# If not, there's a bug somewhere.  But let's at
		# least produce valid HTML code regardless.
		$this->width = round( $width );
		$this->height = round( $height );
		$this->path = $path;
		$this->page = $page;
	}

	/**
	 * Wikia change start
	 * @author Garth
	 */
	function mediaType() {
		return 'image';
	}

	function renderView( array $options = array() ) {
		WikiaLogger::instance()->debug( 'Media method '.__METHOD__.' called',
			array_merge( $options, [
				'url'       => $this->url,
				'method'    => __METHOD__,
				'page'      => $this->page,
				'mediaType' => $this->mediaType(),
				'fileType'  => get_class( $this->file )
			] ) );

		// Make sure to trim the output so that there is no leading whitespace.  The output of this method
		// may be fed back into code that will be parsed for wikitext and leading whitespace will be
		// wrap this HTML in <pre> tags.  VID-1819
		$html = trim( F::app()->renderView( 'ThumbnailController', $this->mediaType(), [
			'thumb'   => $this,
			'options' => $options,
		] ) );
		// Strip empty space between tags
		$html = preg_replace( "/>\s+</", "><", $html );

		return $html;
	}
	/**
	 * Wikia change end
	 */

	/**
	 * Return HTML <img ... /> tag for the thumbnail, will include
	 * width and height attributes and a blank alt text (as required).
	 *
	 * @param $options array Associative array of options. Boolean options
	 *     should be indicated with a value of true for true, and false or
	 *     absent for false.
	 *
	 *     alt          HTML alt attribute
	 *     title        HTML title attribute
	 *     desc-link    Boolean, show a description link
	 *     file-link    Boolean, show a file download link
	 *     valign       vertical-align property, if the output is an inline element
	 *     img-class    Class applied to the \<img\> tag, if there is such a tag
	 *     desc-query   String, description link query params
	 *     custom-url-link    Custom URL to link to
	 *     custom-title-link  Custom Title object to link to
	 *     custom target-link Value of the target attribute, for custom-target-link
	 *
	 * For images, desc-link and file-link are implemented as a click-through. For
	 * sounds and videos, they may be displayed in other ways.
	 *
	 * @throws MWException
	 * @return string
	 */
	function toHtml( $options = array() ) {
		if ( count( func_get_args() ) == 2 ) {
			throw new MWException( __METHOD__ .' called in the old style' );
		}

		WikiaLogger::instance()->debug('Media method '.__METHOD__.' called',
			array_merge( $options, [
				'url'       => $this->url,
				'method'    => __METHOD__,
				'page'      => $this->page,
				'mediaType' => $this->mediaType(),
				'fileType'  => get_class( $this->file )
			] ) );

		$alt = empty( $options['alt'] ) ? '' : $options['alt'];

		$query = empty( $options['desc-query'] )  ? '' : $options['desc-query'];

		if ( !empty( $options['custom-url-link'] ) ) {
			$linkAttribs = array( 'href' => $options['custom-url-link'] );
			if ( !empty( $options['title'] ) ) {
				$linkAttribs['title'] = $options['title'];
			}
			if ( !empty( $options['custom-target-link'] ) ) {
				$linkAttribs['target'] = $options['custom-target-link'];
			}
		} elseif ( !empty( $options['custom-title-link'] ) ) {
			$title = $options['custom-title-link'];
			$linkAttribs = array(
				'href' => $title->getLinkURL(),
				'title' => empty( $options['title'] ) ? $title->getFullText() : $options['title']
			);
		} elseif ( !empty( $options['desc-link'] ) ) {
			$linkAttribs = $this->getDescLinkAttribs( empty( $options['title'] ) ? null : $options['title'], $query );
		} elseif ( !empty( $options['file-link'] ) ) {
			$linkAttribs = array( 'href' => $this->file->getURL() );
		} else {
			$linkAttribs = false;
		}

		$attribs = array(
			'alt' => $alt,
			'src' => $this->url,
			'width' => $this->width,
			'height' => $this->height,
		);
		if ( !empty( $options['valign'] ) ) {
			$attribs['style'] = "vertical-align: {$options['valign']}";
		}
		if ( !empty( $options['img-class'] ) ) {
			$attribs['class'] = $options['img-class'];
		}

		/**
		 * Wikia change begin
		 * @author Federico "Lox" Lucignano <federico@wikia-inc.com>
		 * @author Liz Lee
		 */
		$fileTitle = $this->file->getTitle();
		if ( $fileTitle instanceof Title ) {
			$attribs['data-image-name'] = htmlspecialchars($fileTitle->getText());
			$attribs['data-image-key'] = htmlspecialchars(urlencode($fileTitle->getDBKey()));
		}

		$html = $this->linkWrap( $linkAttribs, Xml::element( 'img', $attribs ) );

		//give extensions a chance to modify the markup
		wfRunHooks( 'ThumbnailImageHTML', array( $options, $linkAttribs, $attribs, $this->file,  &$html ) );

		return $html;
		/**
		 * Wikia change end
		 */
	}

}

/**
 * Basic media transform error class
 *
 * @ingroup Media
 */
class MediaTransformError extends MediaTransformOutput {
	var $htmlMsg, $textMsg, $width, $height, $url, $path;

	function __construct( $msg, $width, $height /*, ... */ ) {
		$args = array_slice( func_get_args(), 3 );
		$htmlArgs = array_map( 'htmlspecialchars', $args );
		$htmlArgs = array_map( 'nl2br', $htmlArgs );

		$this->htmlMsg = wfMessage( $msg )->rawParams( $htmlArgs )->escaped();
		$this->textMsg = wfMessage( $msg )->rawParams( $htmlArgs )->text();
		$this->width = intval( $width );
		$this->height = intval( $height );
		$this->url = false;
		$this->path = false;
	}
	/**
	 * Wikia change start
	 * @author Garth
	 */
	// Keep the same error functionality as before
	function renderView ( $options = array() ) {
		return $this->toHtml( $options );
	}
	/**
	 * Wikia change end
	 */

	function toHtml( $options = array() ) {
		return "<div class=\"MediaTransformError\" style=\"" .
			"width: {$this->width}px; height: {$this->height}px; display:inline-block;\">" .
			$this->htmlMsg .
			"</div>";
	}

	function toText() {
		return $this->textMsg;
	}

	function getHtmlMsg() {
		return $this->htmlMsg;
	}

	function isError() {
		return true;
	}
}

/**
 * Shortcut class for parameter validation errors
 *
 * @ingroup Media
 */
class TransformParameterError extends MediaTransformError {
	function __construct( $params ) {
		parent::__construct( 'thumbnail_error',
			max( isset( $params['width']  ) ? $params['width']  : 0, 120 ),
			max( isset( $params['height'] ) ? $params['height'] : 0, 120 ),
			wfMsg( 'thumbnail_invalid_params' ) );
	}
}
