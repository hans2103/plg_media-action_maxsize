<?php

/**
 * @package     Joomla.Plugin
 * @subpackage  Media-Action.maxsize
 *
 * @copyright   Copyright (C) 2025 HKweb <https://hkweb.nl>
 * @license     GNU General Public License version 3; see LICENSE.txt
 */

namespace Joomla\Plugin\MediaAction\Maxsize\Extension;

use Joomla\CMS\Event\Model\BeforeSaveEvent;
use Joomla\CMS\Image\Image;
use Joomla\Component\Media\Administrator\Plugin\MediaActionPlugin;
use Joomla\Event\SubscriberInterface;

// phpcs:disable PSR1.Files.SideEffects
\defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Media Manager Max Size Action
 * Automatically resizes images to a maximum dimension on the longest side
 *
 * @since  1.0.0
 */
final class Maxsize extends MediaActionPlugin implements SubscriberInterface
{
	/**
	 * Returns an array of events this subscriber will listen to.
	 *
	 * @return  array
	 *
	 * @since   1.0.0
	 */
	public static function getSubscribedEvents(): array
	{
		return array_merge(parent::getSubscribedEvents(), [
			'onContentBeforeSave' => 'onContentBeforeSave',
		]);
	}

	/**
	 * The save event. Resize images to max dimension on longest side.
	 *
	 * @param   BeforeSaveEvent $event  The event instance
	 *
	 * @return  void
	 *
	 * @since   1.0.0
	 */
	public function onContentBeforeSave(BeforeSaveEvent $event): void
	{
		$context = $event->getContext();
		$item    = $event->getItem();

		// Only process media files
		if ($context != 'com_media.file') {
			return;
		}

		// Get max dimension from plugin parameters
		$maxDimension = (int) $this->params->get('max_dimension', 3000);

		// If no max dimension set, skip processing
		if (!$maxDimension) {
			return;
		}

		// Only process image files
		if (!\in_array(strtolower($item->extension), ['jpg', 'jpeg', 'png', 'gif', 'webp', 'avif'])) {
			return;
		}

		// Check if AVIF is supported
		if (strtolower($item->extension) === 'avif' && !\function_exists('imageavif')) {
			return;
		}

		// Create image object from uploaded data
		$imgObject = new Image(imagecreatefromstring($item->data));

		// Get current dimensions
		$currentWidth  = $imgObject->getWidth();
		$currentHeight = $imgObject->getHeight();

		// Check if image needs resizing (if either dimension exceeds max)
		if ($currentWidth <= $maxDimension && $currentHeight <= $maxDimension) {
			return;
		}

		// Calculate new dimensions maintaining aspect ratio
		// The longest side will be $maxDimension
		if ($currentWidth > $currentHeight) {
			// Landscape or square
			$newWidth  = $maxDimension;
			$newHeight = (int) round(($maxDimension / $currentWidth) * $currentHeight);
		} else {
			// Portrait
			$newHeight = $maxDimension;
			$newWidth  = (int) round(($maxDimension / $currentHeight) * $currentWidth);
		}

		// Resize the image
		$imgObject->resize(
			$newWidth,
			$newHeight,
			false,
			Image::SCALE_INSIDE
		);

		// Determine image type
		switch (strtolower($item->extension)) {
			case 'gif':
				$type = IMAGETYPE_GIF;
				break;
			case 'png':
				$type = IMAGETYPE_PNG;
				break;
			case 'avif':
				$type = IMAGETYPE_AVIF;
				break;
			case 'webp':
				$type = IMAGETYPE_WEBP;
				break;
			default:
				$type = IMAGETYPE_JPEG;
		}

		// Generate the new image data
		ob_start();
		$imgObject->toFile(null, $type);
		$item->data = ob_get_clean();
	}
}
