<?php
/**
 * Manejo de datos de productos
 */
class Modelos_Productos extends Sfphp_Modelo 
{
	/**
	 * Obiene los datos de un producto
	 * @param  string $id Id del producto
	 * @return array
	 */
	public function get($id = '')
	{
		$where = NULL;
		$query = "
		SELECT producto, descripcion, descripcionCorta, precio, activo
		FROM productos ";
		if(trim($id) != "")
			$where = " WHERE producto = {$id};";
		return $this->db->query($query.$where);
	}

	/**
	 * Obiene los datos de un producto por la clave
	 * @param  string $clave clave del producto
	 * @return array
	 */
	public function getByClave($clave = '')
	{
		$where = NULL;
		$query = "
		SELECT producto, clave, lineaProducto, descripcionCorta, precio, unidadMedida, iva, ieps
		FROM productos ";
		$where = " WHERE clave = '{$clave}' AND activo = 1;";
		return $this->db->query($query.$where);
	}

	/**
	 * Inserta un nuevo producto
	 * @param  array $data Datos del producto
	 * @return array
	 */
	public function post($data)
	{
		$query = "REPLACE INTO productos
		SET
			clave = '{$data['clave']}',
			descripcion = '{$data['descripcion']}',
			descripcionCorta = '{$data['descripcionCorta']}',
			lineaProducto = '{$data['lineaProducto']}',
			precio = '{$data['precio']}',
			unidadMedida = '{$data['unidadMedida']}',
			iva = '{$data['iva']}',
			ieps = '{$data['ieps']}',
			activo = 1;";
		return $this->db->insert($query);
	}

	/**
	 * Elimina un producto
	 * @param  string $id Id del producto
	 * @return array
	 */
	public function del($id)
	{
		$query = "UPDATE productos
		SET activo = 0 
		WHERE lineaProducto = {$id};";
		return $this->db->query($query.$where);
	}

	/**
	 * Devuelve todos los productos para dibujar el grid
	 * @return array
	 */
	public function grid()
	{
		$query = "SELECT 
			clave Producto, descripcionCorta Descripcion, 
			precio Precio, activo Activo
		FROM
			productos;";
		return $this->db->query($query);
	}

	/**
	 * Los productos con más ventas
	 * @return array
	 */
	public function masVendidos()
	{
		$query = "SELECT det.producto, pro.descripcionCorta, COUNT(distinct det.venta) ventas
			FROM ventasProductos det
			INNER JOIN productos pro USING (producto)
			LIMIT 5;";
		return $this->db->query($query);
	}
}