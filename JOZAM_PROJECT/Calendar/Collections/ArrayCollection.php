<?php

/*
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the MIT license. For more information, see
 * <http://www.doctrine-project.org>.
 */
namespace Collections;

include ('Collection.php');
include ('Selectable.php');

use ArrayIterator;
use Closure;
use Collections\Expr\ClosureExpressionVisitor;

/**
 * An ArrayCollection is a Collection implementation that wraps a regular PHP array.
 *
 * @since 2.1
 * @author Guilherme Blanco <guilhermeblanco@hotmail.com>
 * @author Jonathan Wage <jonwage@gmail.com>
 * @author Roman Borschel <roman@code-factory.org>
 * @author Jaafar Bouayad <bouayad.jaafar@gmail.com>
 */
class ArrayCollection implements Collection, Selectable {
	/**
	 * An array containing the entries of this collection.
	 *
	 * @var array
	 */
	private $elements;
	
	/**
	 * Initializes a new ArrayCollection.
	 *
	 * @param array $elements        	
	 */
	public function __construct(array $elements = array(), $recursive = true) {
		$this->elements = $elements;
		if ($recursive) {
			foreach ( $this->elements as $key => $a ) {
				if (is_array ( $a )) {
					$this->elements [$key] = new ArrayCollection ( $a );
				}
			}
		}
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function toArray() {
		return $this->elements;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function first() {
		return reset ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function last() {
		return end ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function key() {
		return key ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function next() {
		return next ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function current() {
		return current ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function remove($key) {
		if (! isset ( $this->elements [$key] ) && ! array_key_exists ( $key, $this->elements )) {
			return null;
		}
		
		$removed = $this->elements [$key];
		unset ( $this->elements [$key] );
		
		return $removed;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function removeElement($element) {
		$key = array_search ( $element, $this->elements, true );
		
		if ($key === false) {
			return false;
		}
		
		unset ( $this->elements [$key] );
		
		return true;
	}
	
	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetExists($offset) {
		return $this->containsKey ( $offset );
	}
	
	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetGet($offset) {
		return $this->get ( $offset );
	}
	
	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetSet($offset, $value) {
		if (! isset ( $offset )) {
			return $this->add ( $value );
		}
		
		$this->set ( $offset, $value );
	}
	
	/**
	 * Required by interface ArrayAccess.
	 *
	 * {@inheritDoc}
	 */
	public function offsetUnset($offset) {
		return $this->remove ( $offset );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function containsKey($key) {
		return isset ( $this->elements [$key] ) || array_key_exists ( $key, $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function contains($element) {
		return in_array ( $element, $this->elements, true );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function exists(Closure $p) {
		foreach ( $this->elements as $key => $element ) {
			if ($p ( $key, $element )) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function indexOf($element) {
		return array_search ( $element, $this->elements, true );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function get($key) {
		return isset ( $this->elements [$key] ) ? $this->elements [$key] : null;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getKeys() {
		return array_keys ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function getValues() {
		return array_values ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function count() {
		return count ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function set($key, $value) {
		$this->elements [$key] = $value;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function add($value) {
		$this->elements [] = $value;
		
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function isEmpty() {
		return empty ( $this->elements );
	}
	
	/**
	 * Required by interface IteratorAggregate.
	 *
	 * {@inheritDoc}
	 */
	public function getIterator() {
		return new ArrayIterator ( $this->elements );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function map(Closure $func) {
		return new static ( array_map ( $func, $this->elements ) );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function filter(Closure $p) {
		return new static ( array_filter ( $this->elements, $p ) );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function forAll(Closure $p) {
		foreach ( $this->elements as $key => $element ) {
			if (! $p ( $key, $element )) {
				return false;
			}
		}
		
		return true;
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function partition(Closure $p) {
		$matches = $noMatches = array ();
		
		foreach ( $this->elements as $key => $element ) {
			if ($p ( $key, $element )) {
				$matches [$key] = $element;
			} else {
				$noMatches [$key] = $element;
			}
		}
		
		return array (
				new static ( $matches ),
				new static ( $noMatches ) 
		);
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function clear() {
		$this->elements = array ();
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function slice($offset, $length = null) {
		return array_slice ( $this->elements, $offset, $length, true );
	}
	
	/**
	 * {@inheritDoc}
	 */
	public function matching(Criteria $criteria) {
		$expr = $criteria->getWhereExpression ();
		$filtered = $this->elements;
		
		if ($expr) {
			$visitor = new ClosureExpressionVisitor ();
			$filter = $visitor->dispatch ( $expr );
			$filtered = array_filter ( $filtered, $filter );
		}
		
		if ($orderings = $criteria->getOrderings ()) {
			foreach ( array_reverse ( $orderings ) as $field => $ordering ) {
				$next = ClosureExpressionVisitor::sortByField ( $field, $ordering == Criteria::DESC ? - 1 : 1 );
			}
			
			usort ( $filtered, $next );
		}
		
		$offset = $criteria->getFirstResult ();
		$length = $criteria->getMaxResults ();
		
		if ($offset || $length) {
			$filtered = array_slice ( $filtered, ( int ) $offset, $length );
		}
		
		return new static ( $filtered );
	}
	
	/* Added utility functions for JOZAM Project, by Jaafar Bouayad */
	/**
	 * Returns a string representation of this object.
	 *
	 * @return string
	 */
	public function __toString() {
		$return = '[';
		foreach ( $this->elements as $o ) {
			$return = $return . $o . ', <br>';
		}
		$return = ($this->isEmpty () ? $return : substr ( $return, 0, - 6 )) . ']';
		return $return;
	}
	
	/**
	 * Tests if the object is an ArrayCollection.
	 *
	 * @return boolean TRUE if the object is an ArrayCollection, FALSE otherwise.
	 */
	public static function is_ArrayCollection($object) {
		return is_a ( $object, get_class () );
	}
	
	/**
	 * Add all the elements to the ArrayCollection.
	 *
	 * @param
	 *        	ArrayCollection ($elements
	 *        	
	 * @return void
	 */
	public function addAll($elements) {
		foreach ( $elements as $v ) {
			$this->add ( $v );
		}
		
		return true;
	}
	
	/**
	 * Remove all the elements from the ArrayCollection.
	 *
	 * @param ArrayCollection $values        	
	 *
	 * @return void
	 */
	public function removeAllElements($elements) {
		foreach ( $elements as $e ) {
			$this->removeElement ( $e );
		}
	}
	
	/**
	 * Returns the queue of the ArrayCollection.
	 *
	 * @return ArrayCollection
	 */
	public function queue() {
		$queue = new ArrayCollection ( $this->slice ( 1 ) );
		return $queue;
	}
	
	/**
	 * Sorts the ArrayCollection in accordance with the sort function.
	 *
	 * @param ArrayCollection $sortFunction
	 *        	The comparison function must return an integer
	 *        	less than, equal to, or greater than zero
	 *        	if the first argument is considered to be respectively
	 *        	less than, equal to, or greater than the second.
	 *        	
	 * @return void
	 */
	public function sort($sortFunction) {
		usort ( $this->elements, $sortFunction );
	}
	
	/**
	 * Recursively converts nested array into a flat one.
	 */
	function flatten() {
		$result = new ArrayCollection ();
		foreach ( $this->elements as $key => $value ) {
			if (ArrayCollection::is_ArrayCollection ( $value )) {
				$subArrayCollection = $this->get ( $key );
				$subArrayCollection->flatten ();
				$result->addAll ( $subArrayCollection );
			} else {
				$result->add ( $value );
			}
		}
		$this->clear ();
		$this->addAll ( $result );
	}
}
