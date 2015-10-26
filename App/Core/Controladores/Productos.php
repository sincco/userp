<?php
/**
 * Operaciones con productos
 */
class Controladores_Productos extends Sfphp_Controlador
{
	/**
	 * Muestra el grid del catálogo de productos
	 * @return none
	 */
	public function inicio()
	{
		$this->_vista->catalogo = $this->modeloProductos->grid();
		$this->vistaProductos;
	}

	/**
	 * Muestra el formulario de captura de productos
	 * @return none
	 */
	public function nuevo()
	{
		$this->_vista->lineas = $this->modeloLineasproductos->get();
		$this->vistaProductosAlta;
	}

	/**
	 * Muestra el formulario de edicion de productos
	 * @return none
	 */
	public function edicion()
	{
		$data = Sfphp_Peticion::get()['_parametros'];
		$this->_vista->producto = $this->modeloProductos->getByClave($data['clave']);
		$this->vistaProductosEdicion;
	}

	/**
	 * Llamada AJAX para insertar producto
	 * @return json
	 */
	public function apiPost()
	{
		$data = Sfphp_Peticion::get()['_parametros'];
		echo json_encode(array("respuesta"=>$this->modeloProductos->post($data)));
	}

	/**
	 * Llamada AJAX para insertar producto
	 * @return json
	 */
	public function apiActualizar()
	{
		$data = Sfphp_Peticion::get()['_parametros'];
		echo json_encode(array("respuesta"=>$this->modeloProductos->update($data)));
	}

	/**
	 * Regresa los datos del producto según su clave
	 * @return json
	 */
	public function apiClave()
	{
		$clave = Sfphp_Peticion::get()['_parametros']['clave'];
		echo json_encode(array("respuesta"=>$this->modeloProductos->getByClave($clave)));	
	}

	/**
	 * Regresa los datos del producto según su descripción
	 * @return json
	 */
	public function apiDescripcion()
	{
		$descripcion = Sfphp_Peticion::get()['_parametros']['descripcion'];
		echo json_encode(array("respuesta"=>$this->modeloProductos->getByDescripcion($descripcion)));	
	}
}