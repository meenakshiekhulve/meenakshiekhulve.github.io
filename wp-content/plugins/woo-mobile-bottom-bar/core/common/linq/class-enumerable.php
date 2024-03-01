<?php

namespace MABEL_WCBB\Core\Common\Linq
{
	use Exception;
	use ArrayIterator;

	class Enumerable
	{
		private $iterator;

		private function __construct (ArrayIterator $iterator)
		{
			$this->iterator = $iterator;
			// Set iterator back to the 1st element.
			$this->iterator->rewind();
		}

		public static function from ($source)
		{
			// Only ArrayIterator possible atm.
			$iterator = null;

			if ($source instanceof Enumerable)
				return $source;
			if (is_array($source))
				$iterator = new ArrayIterator($source);

			if ($iterator !== null)
			{
				return new Enumerable($iterator);
			}
			throw new Exception("Invalid source for Enumerable.");
		}

		#region Query functions

		/**
		 * @param $predicate
		 *
		 * @return \MABEL_WCBB\Core\Common\Linq\Enumerable
		 */
		public function select($predicate)
		{
			$predicate = Linq_Helpers::create_lambda($predicate);

			// Back to 1st.
			$this->iterator->rewind();

			$objects = array();

			while ($this->iterator->valid())
			{
				array_push($objects,$predicate($this->iterator->current(), $this->iterator->key()));
				$this->iterator->next();
			}
			return self::from($objects);
		}

		/**
		 * @param $predicate string | \Closure
		 * @return Enumerable
		 */
		public function where ($predicate)
		{
			$predicate = Linq_Helpers::create_lambda($predicate);

			// Back to 1st.
			$this->iterator->rewind();

			// while items
			$keys = array();
			while ($this->iterator->valid())
			{
				// Remove from iterator when predicate not true.
				if(!$predicate($this->iterator->current(), $this->iterator->key()))
					array_push($keys, $this->iterator->key());
				$this->iterator->next();
			}

			foreach($keys as $key){
				$this->iterator->offsetUnset($key);
			}

			return $this;
		}

		/**
		 * @param $predicate string|\Closure
		 *
		 * @return Enumerable|null
		 */
		public function firstOrDefault($predicate)
		{
			$predicate = Linq_Helpers::create_lambda($predicate);

			$this->iterator->rewind();
			if(!$this->iterator->valid()) return null;

			// while items
			while ($this->iterator->valid())
			{
				// Push onto result if predicate returns true.
				if($predicate($this->iterator->current(), $this->iterator->key()))
					return $this->iterator->current();
				$this->iterator->next();
			}

			return null;
		}

		#endregion

		#region Boolean Functions
		public function any($predicate = null)
		{
			if($predicate === null)
				return iterator_count($this->iterator) > 0;

			return $this->firstOrDefault($predicate) != null;
		}

		#endregion

		#region Integer Functions
		public function count($predicate = null)
		{
			if($predicate === null)
				return iterator_count($this->iterator);
			return iterator_count($this->where($predicate)->iterator);
		}
		#endregion

		#region String Functions

		/**
		 * Joins an array of objects by generating a string of values with separators.
		 * @param $value string
		 * @param $separator string
		 * @return string
		 */
		public function join($value_predicate, $separator)
		{
			$value = Linq_Helpers::create_lambda($value_predicate);

			$this->iterator->rewind();

			$result = array();
			while ($this->iterator->valid())
			{
				// Push onto result if predicate returns true.
				array_push($result, $value($this->iterator->current(),$this->iterator->key()));
				$this->iterator->next();
			}

			return join($separator, $result);
		}

		#endregion

		#region Operations
		/**
		 * Turn a list of lists into a list. Only goes 2 levels deep at this point.
		 */
		public function flatten()
		{
			$flat = array();

			$this->iterator->rewind();
			while ($this->iterator->valid())
			{
				if(is_array($this->iterator->current())){
					foreach($this->iterator->current() as $e){
						array_push($flat,$e);
					}
				}
				$this->iterator->next();
			}

			return self::from($flat);
		}
		#endregion

		#region Conversion Functions
		/**
		 * @return array
		 */
		public function toArray()
		{
			$this->iterator->rewind();

			if ($this->iterator instanceof ArrayIterator)
				return $this->iterator->getArrayCopy();

			$result = array();
			foreach ($this->iterator as $k => $v) {
				$result[ $k ] = $v;
			}
			return $result;
		}
		#endregion

	}
}