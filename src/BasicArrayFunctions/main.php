<?php

/**
 * Array path işlemlerinde kullanılılan ayıraç.
 */
if(! defined('ARRAY_PATH_DELIMITER'))
	define('ARRAY_PATH_DELIMITER', '.');

/**
 * Array path'inin bir üstün adresini döndürür.
 */
if(! function_exists("array_parent_path"))
{
    function array_parent_path($path)
    {
        return array_slice( explode(ARRAY_PATH_DELIMITER, $path), 0, -1 );
    }
}

/**
 * @version 1.0.0
 * Merge more arrays by values.
 */
if (!function_exists("array_val_joiner"))
{
	function array_val_joiner()
	{
		$args = func_get_args();

		if( count( $args ) < 2 )
			return false;

		$template = array_shift( $args );
		$array = array_shift( $args );

		// Eğer gönderilenler array değilse çık!
		if( !is_array( $template ) || !is_array( $array ) )
			return false;

		// Eğer gönderilenler iki elamamdan fazlaysa;
		// fonksiyonu içten içe yönlendir ve array'ı topla.
		if( count( $args ) > 0 )
		{
			array_unshift( $args, $array );
			$array = call_user_func_array( __FUNCTION__, $args );
			if( $array == false )
				return false;
		}

		// Arrayda olmayanlar template'den aktarılıyor.
		foreach( $template as $key=>$value )
		{
			if( array_search( $value, $array ) === false )
				if( array_key_exists( $key, $array ) === false )
					$array[ $key ] = $value;
				else
					$array[] = $value;
		}

		return $array;
	}
}

/*
 * @version 1.0.0
 *
 * Gönderilen taslak array ile normal array'ı kıyaslar ve normal array'a eksik olan key'leri ekler.
 * Birden fazla array gönderilebilir. En baskın array en son yazılan array'dır!!!
 */
if (!function_exists("array_key_joiner"))
{
	function array_key_joiner()
	{
		$args = func_get_args();

		if( count( $args ) < 2 )
            throw new \Exception("Argument miss!");

		// Template üstüne veri eklenecek olan array.
		$template = array_shift( $args );

		// Eğer gönderilenler array değilse çık!
		if( !is_array( $template ) )
			throw new \Exception("Argument is not Array!");

		// Eğer gönderilenler iki elamamdan fazlaysa;
		// fonksiyonu içten içe yönlendir ve primary değişkenini tek bir array haline getir.
		if( count( $args ) > 1 )
		{
			$primary = call_user_func_array( __FUNCTION__, $args );
			if( $primary == false )
				return false;
		}
		else
		{
			if( !is_array($primary = array_shift( $args )) )
                throw new \Exception("Argument is not Array!");
		}

		// Primary'daki bütün veriler Template'e aktarılıyor.
		foreach( $primary as $key => $val )
		{
			$template[ $key ] = $val;
		}

		return $template;

	}
}

if (!function_exists("array_key_joiner_recursive"))
{
	function array_key_joiner_recursive()
	{
		$args = func_get_args();

		if( count( $args ) < 2 )
            throw new \Exception("Argument miss!");

		// Template üstüne veri eklenecek olan array.
		$template = array_shift( $args );

		// Eğer gönderilenler iki elamamdan fazlaysa;
		// fonksiyonu içten içe yönlendir ve primary değişkenini tek bir array haline getir.
		if( count( $args ) > 1 )
		{
			$primary = call_user_func_array( __FUNCTION__, $args );
			if( $primary == false )
				return false;
		}
		else
		{
			if( !is_array($primary = array_shift( $args )) )
                throw new \Exception("Argument is not Array!");
		}

		foreach ($primary as $key => $val)
		{
			// Eğer eşitlenecek öğe Array ise ve
			// Template array'ında bu değer var ise ve
			// Template array'ında bu key de array bulunduruyorsa
			// Fonksiyonu iç içe çağır.
			if( is_array($val) && isset( $template[ $key ] ) && is_array( $template[ $key ] ) )
				$template[ $key ] = call_user_func_array( __FUNCTION__, array( $template[$key], $primary[$key] ) );
			else
				$template[ $key ] = $val;
		}

		return $template;
	}
}

/**
 * @version 1.0.1
 * Gelişmiş bir veri çekme fonksiyonudur.
 */
if (!function_exists("array_get"))
{
	function array_get( &$data, $path )
	{

		$args = func_get_args();

		// default_return var ise..
		if (isset($args[2]))
		{
			$default_return = $args[2];
		}

		//	Yol Parçalanıyor.
		$path = explode(ARRAY_PATH_DELIMITER, $path);

		//	Hedef olarak ile data'yı seç.
		$target_data = $data;

        try
        {
            //	Yolları tek tek git.
            foreach ($path as $way)
            {
                //	Yolu aşama aşama git ve hedefi daralt.
                $target_data = $target_data[$way];
            }
        }
        // Eğer istenen öğre bulunamaz ise hata alanına girecek.
        catch (\Exception $e)
        {
            //	default değerin basılması isteniyor ise..
            if ( isset($default_return) )
            {
                return array_set( $data, implode(ARRAY_PATH_DELIMITER, $path), $default_return );
            }
            //	default basılması isstenmiyorsa ise..
            else
            {
                throw new \Exception('Path can\'t find! Path:' . impode(ARRAY_PATH_DELIMITER, $path));
            }
        }


		return $target_data;
	}
}

/**
 * @version 1.0.1
 *
 * Array'ın path'deki yerini değiştiren fonksiyondur.
 */
if (!function_exists("array_set"))
{
	function array_set( &$data, $path, $val )
	{
        return array_key_joiner_recursive($data, array_patch($path, $val));
	}
}

/**
 | @version 1.0.0
 | @author Ömer Kala <kalaomer@hotmail.com>
 | Array'a patch oluşturur.
 */
if(! function_exists("array_patch"))
{
	function array_patch($path, $value)
	{
		$result = $value;

		foreach(array_reverse( explode(ARRAY_PATH_DELIMITER, $path) ) as $pathPart)
		{
			$result = Array($pathPart => $result);
		}

		return $result;
	}
}

/**
 * @version 1.0.0
 * @author Ömer Kala <kalaomer@hotmail.com>
 * Array'ın path'deki yerini değiştiren fonksiyondur.
 */
if (!function_exists("array_del"))
{
	function array_del( &$data, $path )
	{
		
		//	Yol Parçalanıyor.
		$path = explode(".", $path);
		
		//	En son değiştirilecek olan yerin adresi.
		$target = array_pop($path);
		
		$target_data = &$data;

		foreach ($path as $way)
		{
			if (!array_key_exists($target_data, $way))
				return;
		
			$target_data = &$target_data[$way];
		}

		unset( $target_data[ $target ] );
	}
}

if (!function_exists("array_order_by")) {

	/**
	 * @name Mutlidimensional Array Sorter.
	 * @author Tufan Barış YILDIRIM
	 * @link http://www.tufanbarisyildirim.com
	 * @github http://github.com/tufanbarisyildirim
	 *
	 * This function can be used for sorting a multidimensional array by sql like order by clause
	 *
	 * @param mixed $array
	 * @param mixed $order_by
	 * @return array
	 */
	function array_order_by( array $array, $order_clause )
	{
		//TODO -c flexibility -o tufanbarisyildirim : this error can be deleted if you want to sort as sql like "NULL LAST/FIRST" behavior.
		if(!is_array($array[0]))
			throw new Exception('Object is not a multidimensional array!',E_USER_ERROR);

		$columns = explode(',',$order_clause);
		foreach ($columns as $col_dir)
		{
			if(preg_match('/(.*)([\s]+)(ASC|DESC)/is',$col_dir,$matches))
			{
				if(!array_key_exists(trim($matches[1]),$array[0]))
					trigger_error('Unknown Column <b>' . trim($matches[1]) . '</b>',E_USER_NOTICE);
				else
				{
					if(isset($sorts[trim($matches[1])]))
						trigger_error('Redundand specified column name : <b>' . trim($matches[1] . '</b>'));

					$sorts[trim($matches[1])] = 'SORT_'.strtoupper(trim($matches[3]));
				}
			}
			else
			{
				throw new Exception("Incorrect syntax near : '{$col_dir}'",E_USER_ERROR);
			}
		}

		//TODO -c optimization -o tufanbarisyildirim : use array_* functions.
		$colarr = array();
		foreach ($sorts as $col => $order)
		{
			$colarr[$col] = array();
			foreach ($array as $k => $row)
			{
				$colarr[$col]['_'.$k] = strtolower($row[$col]);
			}
		}

		$multi_params = array();
		foreach ($sorts as $col => $order)
		{
			$multi_params[] = '$colarr[\'' . $col .'\']';
			$multi_params[] = $order;
		}

		$rum_params = implode(',',$multi_params);
		eval("array_multisort({$rum_params});");


		$sorted_array = array();
		foreach ($colarr as $col => $arr)
		{
			foreach ($arr as $k => $v)
			{
				$k = substr($k,1);
				if (!isset($sorted_array[$k]))
					$sorted_array[$k] = $array[$k];
				$sorted_array[$k][$col] = $array[$k][$col];
			}
		}

		return array_values($sorted_array);

	}
}
