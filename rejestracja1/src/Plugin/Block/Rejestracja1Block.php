<?php

namespace Drupal\rejestracja1\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Form\FormBuilderInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a 'Rejestracja1Block' block.
 *
 * @Block(
 *   id = "rejestracja1_block",
 *   admin_label = @Translation("Rejestracja 1 Block"),
 * )
 */
class Rejestracja1Block extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $form = \Drupal::formBuilder()->getForm('\Drupal\rejestracja1\Form\Rejestracja1Form');
    return $form;
  }

}
