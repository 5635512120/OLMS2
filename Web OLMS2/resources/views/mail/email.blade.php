@extends('layouts.email')
@section('content')
<h3>{{ $user->fromSubject }}</h3>
    	<p>เรื่อง  {{ $user->fromName }}</p>  <br>
    	<p>เรียน  {{ $user->toName }}</p>  <br>
    	<p>	ด้วยข้าพเจ้า {{ $user->fromMessage }} มีความประสงค์จะขอ {{ $user->formats }} 
			เนื่องจาก {{ $user->description }} 
		</p> <br>
		<p>	ดังนั้น ข้าพเจ้าจึงขอลาเป็นจํานวน 1 วัน ตั้งแต่วันที่ {{ $user->Start }} 
			ถึงวันที่ {{ $user->Start }} เมื่อครบกําหนดแล้ว จะมาเรียนตามปกติ</p><br>
		<p>	จึงเรียนมาเพื่อโปรดพิจารณาอนุญาต</p><br>
		<p>ด้วยความเคารพอย่างสูง</p>

@endsection

