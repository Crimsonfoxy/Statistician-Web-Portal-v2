<?php
if(fSession::get('maxStep') != 3) fURL::redirect('?step=two');
