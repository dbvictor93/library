<?php

/**
 * @file
 * Definition of Drupal\views\Tests\Handler\SortRandomTest.
 */

namespace Drupal\views\Tests\Handler;

/**
 * Tests for core Drupal\views\Plugin\views\sort\Random handler.
 */
class SortRandomTest extends HandlerTestBase {

  public static function getInfo() {
    return array(
      'name' => 'Sort: Random',
      'description' => 'Test the core Drupal\views\Plugin\views\sort\Random handler.',
      'group' => 'Views Handlers',
    );
  }

  protected function setUp() {
    parent::setUp();

    $this->enableViewsTestModule();
  }

  /**
   * Add more items to the test set, to make the order tests more robust.
   */
  protected function dataSet() {
    $data = parent::dataSet();
    for ($i = 0; $i < 50; $i++) {
      $data[] = array(
        'name' => 'name_' . $i,
        'age' => $i,
        'job' => 'job_' . $i,
        'created' => rand(0, time()),
      );
    }
    return $data;
  }

  /**
   * Return a basic view with random ordering.
   */
  protected function getBasicRandomView() {
    $view = $this->getView();

    // Add a random ordering.
    $view->displayHandlers['default']->overrideOption('sorts', array(
      'random' => array(
        'id' => 'random',
        'field' => 'random',
        'table' => 'views',
      ),
    ));

    return $view;
  }

  /**
   * Tests random ordering of the result set.
   *
   * @see DatabaseSelectTestCase::testRandomOrder()
   */
  public function testRandomOrdering() {
    // Execute a basic view first.
    $view = $this->getView();
    $this->executeView($view);

    // Verify the result.
    $this->assertEqual(count($this->dataSet()), count($view->result), t('The number of returned rows match.'));
    $this->assertIdenticalResultset($view, $this->dataSet(), array(
      'views_test_data_name' => 'name',
      'views_test_data_age' => 'age',
    ));

    // Execute a random view, we expect the result set to be different.
    $view_random = $this->getBasicRandomView();
    $this->executeView($view_random);
    $this->assertEqual(count($this->dataSet()), count($view_random->result), t('The number of returned rows match.'));
    $this->assertNotIdenticalResultset($view_random, $view->result, array(
      'views_test_data_name' => 'views_test_data_name',
      'views_test_data_age' => 'views_test_data_name',
    ));

    // Execute a second random view, we expect the result set to be different again.
    $view_random_2 = $this->getBasicRandomView();
    $this->executeView($view_random_2);
    $this->assertEqual(count($this->dataSet()), count($view_random_2->result), t('The number of returned rows match.'));
    $this->assertNotIdenticalResultset($view_random, $view->result, array(
      'views_test_data_name' => 'views_test_data_name',
      'views_test_data_age' => 'views_test_data_name',
    ));
  }

}
