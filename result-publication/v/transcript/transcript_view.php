<?php
	$ui = new UI();
	$col1 = $ui->col()->width(4)->open();
	$col1->close();
	$col2 = $ui->col()->width(4)->open();
		$row_2_1=$ui->row()->open();
			$box = $ui->box()
						->title('Enter Details')
						->uiType('primary')
						->solid()
						->open();
			$form = $ui->form()
						->action('transcript/main/get_details')
						->open();
				$ui->input()
					->label('Admission Number')
					->id('admn_no')
					->name('admn_no')
					->placeholder('Enter Admn no.')
					->required()
					->show();
                                
                                $ui->input()
					->label('Enter Number of Rows')
					->id('nrows')
					->name('nrows')
					->value('14')
					->required()
					->show();
                                //-----------------------------------------------------------
                 /*                               $ui->select()
			->label('Select Format')
			->name('pformat')
			->id('pformat')
			//->required()
			->options(array($ui->option()->value('""')->text('Select')->selected(),
							$ui->option()->value('scolumn')->text('Single Column'),
							$ui->option()->value('tcolumn')->text('Two Column'),
													
							))
													
							
		   
		    ->show();
                                
                                //-----------------------------------------------------------------
          
                                $ui->select()
			->label('Select Papersize')
			->name('psize')
			->id('psize')
			//->required()
			->options(array($ui->option()->value('""')->text('Select')->selected(),
							$ui->option()->value('afour')->text('A4'),
							$ui->option()->value('legal')->text('Legal'),
													
							))
													
							
		   
		    ->show();
                        */
                                //------------------------------------------------------
				$ui->button()
					->submit(true)
					->value("Submit")
					->id('submit')
					->uiType("primary")
					->show();
			$form->close();
			$box->close();
		$row_2_1->close();
	$col2->close();
?>