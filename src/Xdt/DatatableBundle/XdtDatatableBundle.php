<?php
/**
 * Xdt DatatableBundle for Symfony 4
 * @author : Jihad Sinnaour
 * @license : The Symfony Licence
 */

namespace App\Xdt\DatatableBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\HttpFoundation\Response;

class XdtDatatableBundle extends Bundle
{
	private $accessor;
	private $data = [];
	private $columns = [];

	/**
	 * Construct Xdt object 
	 * and return Response object
	 *
	 * @param array $data Doctrine Entiy Array
	 * @param array $columns Columns to get
	 * @return {inherit}
	 */
	public function __construct(array $data = null, array $columns = null)
	{
		$this->accessor = PropertyAccess::createPropertyAccessor();
		if ( $data && $columns ) {
			$this->data = $data;
			$this->columns = $columns;
			$this->render();
		}
	}

	/**
	 * Set Doctrine Entiy Array
	 *
	 * @param array $data
	 * @return object
	 */
	public function setData(array $data) : self
	{
		$this->data = $data;
		return $this;
	}

	/**
	 * Set Columns to get
	 *
	 * @param array $columns
	 * @return object
	 */
	public function setColumns(array $columns) : self
	{
		$this->columns = $columns;
		return $this;
	}

	/**
	 * Format data for DataTable
	 *
	 * @param void
	 * @return string
	 */
	private function format() : ?string
	{
		$dWrapper = [];
		foreach ($this->data as $entry) {
			$cWrapper = [];
			foreach ($this->columns as $column) {
				$cWrapper[] = $this->accessor->getValue($entry,$column);
			}
			$dWrapper[] = $cWrapper;
		}
		$json = json_encode($dWrapper);
		$prefix = '"data": ';
		return "{{$prefix}{$json}}";
	}

	/**
	 * Return response
	 *
	 * @param void
	 * @return Object Response
	 */
	public function render() : ?object
	{
		return new Response( $this->format() );
	}
}
