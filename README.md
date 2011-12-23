CakePHP simple_html_dom Datasource Plugin
====

*simple_html_dom* is a HTML DOM parser let you manipulate HTML in a very easy way.

http://simplehtmldom.sourceforge.net/


USAGE
----

* download this plugin

```sh
$ git submodule add git://github.com/monsat/CakePHP-SimpleHtmlDom-Plugin.git your_app/Plugin/SimpleHtmlDom
$ ls your_app/Plugin
SimpleHtmlDom
```

* use datasource

```php
# Config/database.php
class DATABASE_CONFIG {
  public $htmldom = array(
		'datasource' => 'SimpleHtmlDom.SimpleHtmlDomSource',
		'source' => 'http://www.example.com/example.html', // URL or path of contents
	);
}
# Model/Sample.php
class Sample extends AppModel {
  public $useDbConfig = 'htmldom';
  public $source = 'http://www.yahoo.co.jp/'; // URL or path of contents if override db config
}
```

* at controller

```php
public function index() {
  $html = $this->Sample->find('first');
  debug($html->plaintext);
}
```