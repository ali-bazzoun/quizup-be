<?php

abstract class BaseModel
{
	public function __construct(array $data = [])
	{
		foreach ($data as $key => $value)
		{
			if (property_exists($this, $key))
			{
				$this->$key = $value;
			}
		}
	}
}
