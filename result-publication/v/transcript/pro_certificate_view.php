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
						->action('transcript/pro_certificate/get_details')
						->open();
				$ui->input()
					->label('Admission Number')
					->id('admn_no')
					->name('admn_no')
					->placeholder('Enter Admn no.')
					->required()
					->show();
                                
           
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