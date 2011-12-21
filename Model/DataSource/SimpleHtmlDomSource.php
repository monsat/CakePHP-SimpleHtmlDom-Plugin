<?php
/**
 * SimpleHtmlDom Datasource
 *
 * PHP versions 5
 *
 * CakePHP(tm) : Rapid Development Framework (http://cakephp.org)
 * Copyright 2005-2010, Cake Software Foundation, Inc. (http://cakefoundation.org)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2011 Direct Search Japan Inc.
 * @link          http://www.direct-search.jp/
 * @package       SimpleHtmlDomDatasoucre Plugin
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
/*
 * database config
	public $htmldom = array(
	);
*/
App::uses('DataSource', 'Model/Datasource');
App::uses('HttpSocket', 'Network/Http');
/**
 * SimpleHtmlDomSource
 *
 * Datasource by simple_html_dom.php
 */
class SimpleHtmlDomSource extends DataSource {

/**
 * Description string for this Data Source.
 *
 * @var string
 */
	public $description = 'Simple Html Dom Parser Datasource';

/**
 * List of requests ("queries")
 *
 * @var array
 */
	protected $_requestsLog = array();

/**
 * Base Config
 *
 * @var array
 */
	public $_baseConfig = array(
		'driver' => '' // Just to avoid DebugKit warning
	);

/**
 * Constructor
 *
 * Creates new HttpSocket
 *
 * @param array $config Configuration array
 */
	public function __construct($config) {
		parent::__construct($config);
		App::import('HttpSocket');
		$this->Http = new HttpSocket();
	}

/**
 * Returns a Model description (metadata) or null if none found.
 *
 * @param Model $model
 * @return array Show only id
 */
	public function describe(&$model) {
		return array('id' => array());
	}

/**
 * List sources
 *
 * @param mixed $data
 * @return boolean Always false. It's not supported
 */
	public function listSources($data = null) {
		return false;
	}

/**
 * Returns an calculation
 *
 * @param model $model
 * @param string $type Lowercase name type, i.e. 'count' or 'max'
 * @param array $params Function parameters (any values must be quoted manually)
 * @return string Calculation method
 */
	public function calculate(&$model, $type, $params = array()) {
		return 'COUNT';
	}


/**
 * Get the query log as an array.
 *
 * @param boolean $sorted Get the queries sorted by time taken, defaults to false.
 * @param boolean $clear Clear after return logs
 * @return array Array of queries run as an array
 */
	public function getLog($sorted = false, $clear = true) {
		if ($sorted) {
			$log = sortByKey($this->_requestsLog, 'took', 'desc', SORT_NUMERIC);
		} else {
			$log = $this->_requestsLog;
		}
		if ($clear) {
			$this->_requestsLog = array();
		}
		return array('log' => $log, 'count' => count($log), 'time' => array_sum(Set::extract('{n}.took', $log)));
	}

}
