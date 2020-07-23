<?php

/**
 * @file
 * Contains \Drupal\jugaad_patch\Plugin\Block\QRCodeBlock.
 */

namespace Drupal\jugaad_patch\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Endroid\QrCode\QrCode;

/**
 * Provides a 'QrCodeBlock' block.
 *
 * @Block(
 *   id = "QRCodeBlock",
 *   admin_label = @Translation("QR code For Product"),
 *   category = @Translation("Custom QR code block")
 * )
 */
class QRCodeBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    global $base_url;

    // Gets current node id
    $node = \Drupal::routeMatch()->getParameter('node');
    if ($node instanceof \Drupal\node\NodeInterface) {
      // You can get nid and anything else you need from the node object.
      $product_qr_image = 'product-' . $node->id() . '.png';
    }

    // To get real file path
    $directory = 'public://';
    $uri = $directory . $product_qr_image;
    $path = drupal_realpath($uri);

    // Check QR image already created or not
    if (!file_exists($path)) {
      file_prepare_directory($directory, FILE_MODIFY_PERMISSIONS | FILE_CREATE_DIRECTORY);
      $qrCode = new QrCode('product-' . $node->id());
      $qrCode->writeFile($path);
    }

    // Return QR code as image in markup
    $full_path = $base_url . '/sites/default/files/' . $product_qr_image;
    return array(
      '#type' => 'markup',
      '#markup' => '<p>To purchase this product on our app to avail exclusive app-only </p><img src= "' . $full_path . '">'
    );
  }

}
