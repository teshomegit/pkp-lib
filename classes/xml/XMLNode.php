<?php

/**
 * @file classes/xml/XMLNode.php
 *
 * Copyright (c) 2014-2021 Simon Fraser University
 * Copyright (c) 2000-2021 John Willinsky
 * Distributed under the GNU GPL v3. For full terms see the file docs/COPYING.
 *
 * @class XMLNode
 * @ingroup xml
 *
 * @brief Default handler for PKPXMLParser returning a simple DOM-style object.
 * This handler parses an XML document into a tree structure of XMLNode objects.
 */

namespace PKP\xml;

class XMLNode {

	/** @var string the element (tag) name */
	var $name;

	/** @var XMLNode reference to the parent node (null if this is the root node) */
	var $parent;

	/** @var array the element's attributes */
	var $attributes;

	/** @var string the element's value */
	var $value;

	/** @var array references to the XMLNode children of this node */
	var $children;

	/**
	 * Constructor.
	 * @param string $name element/tag name
	 */
	function __construct($name = null) {
		$this->name = $name;
		$this->parent = null;
		$this->attributes = array();
		$this->value = null;
		$this->children = array();
	}

	/**
	 * @param bool $includeNamespace
	 * @return string
	 */
	function getName($includeNamespace = true) {
		if (
			$includeNamespace ||
			($i = strpos($this->name, ':')) === false
		) return $this->name;
		return substr($this->name, $i+1);
	}

	/**
	 * @param string $name
	 */
	function setName($name) {
		$this->name = $name;
	}

	/**
	 * @return XMLNode
	 */
	function &getParent() {
		return $this->parent;
	}

	/**
	 * @param XMLNode $parent
	 */
	function setParent(&$parent) {
		$this->parent =& $parent;
	}

	/**
	 * @return array all attributes
	 */
	function getAttributes() {
		return $this->attributes;
	}

	/**
	 * @param string $name attribute name
	 * @return string attribute value
	 */
	function getAttribute($name) {
		return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
	}

	/**
	 * @param string $name attribute name
	 * @param string $value attribute value
	 */
	function setAttribute($name, $value) {
		$this->attributes[$name] = $value;
	}

	/**
	 * @param array $attributes
	 */
	function setAttributes($attributes) {
		$this->attributes = $attributes;
	}

	/**
	 * @return string
	 */
	function &getValue() {
		return $this->value;
	}

	/**
	 * @param string $value
	 */
	function setValue($value) {
		$this->value =& $value;
	}

	/**
	 * @return array this node's children (XMLNode objects)
	 */
	function &getChildren() {
		return $this->children;
	}

	/**
	 * @param string $name
	 * @param int $index
	 * @return XMLNode the ($index+1)th child matching the specified name
	 */
	function &getChildByName($name, $index = 0) {
		if (!is_array($name)) $name = array($name);
		foreach ($this->children as $key => $junk) {
			$child =& $this->children[$key];
			if (in_array($child->getName(), $name)) {
				if ($index == 0) {
					return $child;
				} else {
					$index--;
				}
			}
			unset($child);
		}
		$child = null;
		return $child;
	}

	/**
	 * Get the value of a child node.
	 * @param string $name name of node
	 * @param int $index Optional index of child node to find
	 */
	function &getChildValue($name, $index = 0) {
		$node =& $this->getChildByName($name);
		if ($node) {
			$returner =& $node->getValue();
		} else {
			$returner = null;
		}
		return $returner;
	}

	/**
	 * @param XMLNode $node the child node to add
	 */
	function addChild(&$node) {
		$this->children[] =& $node;
	}

	/**
	 * @param resource $output file handle to write to, or true for stdout, or null if XML to be returned as string
	 * @return string
	 */
	function &toXml($output = null) {
		$nullVar = null;
		$out = '';

		if ($this->parent === null) {
			// This is the root node. Output information about the document.
			$out .= "<?xml version=\"" . $this->getAttribute('version') . "\" encoding=\"UTF-8\"?>\n";
			if ($this->getAttribute('type') != '') {
				if ($this->getAttribute('url') != '') {
					$out .= "<!DOCTYPE " . $this->getAttribute('type') . " PUBLIC \"" . $this->getAttribute('dtd') . "\" \"" . $this->getAttribute('url') . "\">";
				} else {
					$out .= "<!DOCTYPE " . $this->getAttribute('type') . " SYSTEM \"" . $this->getAttribute('dtd') . "\">";
				}
			}
		}

		if ($this->name !== null) {
			$out .= '<' . $this->name;
			foreach ($this->attributes as $name => $value) {
				$value = XMLNode::xmlentities($value);
				$out .= " $name=\"$value\"";
			}
			if ($this->name !== '!--') {
				$out .= '>';
			}
		}
		$out .= XMLNode::xmlentities($this->value, ENT_NOQUOTES);
		foreach ($this->children as $child) {
			if ($output !== null) {
				if ($output === true) echo $out;
				else fwrite ($output, $out);
				$out = '';
			}
			$out .= $child->toXml($output);
		}
		if ($this->name === '!--') {
			$out .= '-->';
		} else if ($this->name !== null) {
			$out .= '</' . $this->name . '>';
		}
		if ($output !== null) {
			if ($output === true) echo $out;
			else fwrite ($output, $out);
			return $nullVar;
		}
		return $out;
	}

	static function xmlentities($string, $quote_style=ENT_QUOTES) {
		return htmlspecialchars($string, $quote_style, 'UTF-8');
	}

	function destroy() {
		unset($this->value, $this->attributes, $this->parent, $this->name);
		foreach ($this->children as $child) {
			$child->destroy();
		}
		unset($this->children);
	}
}

if (!PKP_STRICT_MODE) {
	class_alias('\PKP\xml\XMLNode', '\XMLNode');
}
