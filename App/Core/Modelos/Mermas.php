<?php
/**
 * Manejo de datos de mermas
 */
class Modelos_Mermas extends Sfphp_Modelo 
{
	/**
	 * Obiene los datos de una merma
	 * @param  string $id Id del producto
	 * @return array
	 */
	public function get($id = '')
	{
		$where = NULL;
		$query = "SELECT 
			producto, clave, descripcion, descripcionCorta, precio, unidadMedida, iva, costo
		FROM mermas ";
		if(trim($id) != "")
			$where = " WHERE producto = {$id};";
		return $this->db->query($query.$where);
	}

	/**
	 * Inserta una nueva merma
	 * @param  array $data Datos del producto
	 * @return array
	 */
	public function post($data)
	{
		$query = "SELECT almacen FROM vendedores WHERE vendedor = '{$data['vendedor']}';";
		$almacen = $this->db->query($query);
		$almacen = $almacen[0];
		$query = "INSERT INTO mermas
		SET
			vendedor = '{$data['vendedor']}',
			producto = '{$data['producto']}',
			motivo = '{$data['motivo']}',
			cantidad = '{$data['cantidad']}',
			fecha = CURDATE();";
		id = $this->db->insert($query);
		$query = "UPDATE almacenesProductos
			SET
				existencias = existencias + {$data['cantidad']}
			WHERE almacen = {$almacen['almacen']} AND
				producto = {$data['producto']};";
		$this->db->query($query);
		$query = "INSERT INTO kardex
			SET producto = '{$data['producto']}',
				fechaHora = CURRENT_TIMESTAMP,
				movimiento = 'Entrada',
				tabla = 'mermas',
				idTabla = '{$id}',
				cantidad = '{$data['cantidad']}',
				precio = '0',
				costo = '0';";
		$this->db->insert($query);
		return $id;
	}

	/**
	 * Devuelve todas los mermas para dibujar el grid
	 * @return array
	 */
	public function grid()
	{
		$query = "SELECT 
			mer.fecha Fecha, mer.motivo Motivo,
			pro.clave Producto, pro.descripcionCorta Descripcion,
			mer.cantidad cantidad
		FROM mermas mer
		INNER JOIN productos pro USING(producto)
		ORDER BY mer.merma DESC;";
		return $this->db->query($query);
	}

	public function detalleVendedor($desde, $hasta, $vendedor)
	{
		$query = "SELECT mer.fecha, usr.nombre, pro.descripcionCorta producto, 
			mer.cantidad, mer.motivo
		FROM mermas mer
		INNER JOIN vendedores ven USING(vendedor)
		INNER JOIN usuarios usr USING(usuario)
		INNER JOIN productos pro USING(producto)
		WHERE mer.fecha between '{$desde}' AND '{$hasta}'";
		if(trim($vendedor) != "0")
			$query .= " AND mer.vendedor = '{$vendedor}'";
		$query .= " GROUP BY mer.fecha, usr.nombre, pro.descripcionCorta;";
		return $this->db->query($query);
	}
}
