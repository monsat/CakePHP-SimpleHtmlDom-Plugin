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
		'datasource' => 'SimpleHtmlDom.SimpleHtmlDomSource',
		'source' => 'http://www.example.com/example.html', // URL or path of contents
	);
*/
App::uses('DataSource', 'Model/Datasource');
App::import('Vendor', 'SimpleHtmlDom.SimpleHtmlDom', array('file' => 'simple_html_dom' . DS . 'simple_html_dom.php'));
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
	}

/**
 * Returns a Model description (metadata) or null if none found.
 *
 * @param Model $model
 * @return array Show only id
 */
	public function describe($model) {
		return array('id' => array());
	}

	public function get(Model $model, $source = null, $isHtml = false) {
		$source = $source ?: $model->source ?: $this->config['source'];
		if (empty($model->Htmls[$source])) {
			if (empty($model->Htmls)) {
				$model->Htmls = array();
			}
			$html = $isHtml ? str_get_html($source) : file_get_html($source);
			$result = !!$model->Htmls[$source] = $html;
			$this->__requestLog[] = sprintf('%s : get from [%s]', $result, $source);
		}
		$model->Html = $model->Htmls[$source];
		return $model->Html;
	}

	public function read(Model $model, $queryData = array(), $recursive = null) {
		$isHtml = !empty($queryData['isHtml']) ? true : false;
		// all
		if ($queryData['limit'] !== 1) {
			if (!$sources = $this->_source($queryData)) {
				return $model->Htmls;
			}
			$results = array();
			foreach ($sources as $source) {
				$results[$source] = $this->get($model, $source, $isHtml);
			}
			return $results;
		}
		// first
		$source = $this->_source($queryData);
		// get
		$this->get($model, $source, $isHtml);
		return array($model->Html);
	}

	private function _source($queryData) {
		$source = null;
		if (!empty($queryData['conditions'])) {
			$source = $queryData['conditions'];
		} else if (!empty($queryData[0])) {
			$source = $queryData[0];
		}
		return $source;
	}

	public function query($method, $params, Model $model) {
		if ($method === 'get') {
			return $this->get($model, $params);
		}
		return call_user_func_array(array($this, $method), $params);
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
		$log = $this->_requestsLog;
		if ($clear) {
			$this->_requestsLog = array();
		}
		return array('log' => $log, 'count' => count($log), 'time' => 0);
	}

}
