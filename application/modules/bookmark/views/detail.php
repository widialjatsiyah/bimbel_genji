<section id="bookmarkDetail">
	<div class="card">
		<div class="card-body">
			<h4 class="card-title"><?php echo (isset($card_title)) ? $card_title : '' ?></h4>
			<hr>
			<div class="row">
				<div class="col-md-12">
					<figure class="text-center">
						<blockquote class="blockquote">
							<p><?php echo (isset($question->question_text)) ? $question->question_text : '' ?></p>
						</blockquote>
						<figcaption class="blockquote-footer">
							Jawaban : <cite title="Source Title">
								<?php
								$answer_correct = $question->correct_option;

								$options = [
									'A' => $question->option_a,
									'B' => $question->option_b,
									'C' => $question->option_c,
									'D' => $question->option_d,
									'E' => $question->option_e,
								];

								$found = '-';

								foreach ($options as $key => $value) {
									if ($answer_correct == $key) {
										$found = $value;
										break;
									}
								}

								echo $found;
								?>
							</cite>
						</figcaption>
					</figure>
				</div>
			</div>
			<?php if (isset($question_text_processed)) { ?>
				<hr>
				<div class="row">
					<div class="col-sm-12">
						<div class="text-center">
							<div class="pembahasan">
								<?php echo $question_text_processed ?>
							</div>
						</div>
					</div>
				</div>
			<?php } ?>
			<div class="row">
				<div class="col-sm-12">
					<h4>Pembahasan:</h4>
					<div class="pembahasan">
						<p><?php echo (isset($question->explanation)) ? $question->explanation : '' ?></p>
					</div>
				</div>

			</div>
			<div class="row">
				<div class="col-sm-12">
					<a href="<?php echo base_url('bookmark') ?>" class="btn btn-light btn--icon-text">
						<i class="zmdi zmdi-arrow-back"></i> Kembali
					</a>
				</div>

		</div>
	</div>
</section>
