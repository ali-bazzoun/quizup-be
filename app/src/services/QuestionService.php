<?php

require_once __DIR__ . '/../repositories/QuestionRepository.php';

class QuestionService
{
	private QuestionRepository $repo;

	public function __construct()
	{
		this->repo = new QuestionRepository();
	}

	public get_valid_questions()
	{
		
	}
	
}