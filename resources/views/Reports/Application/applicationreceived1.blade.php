<?php $i=1; ?>
@foreach($result as $res)
<tr>
  <td>{{$i++}}</td>
  <td>{{$res->appltypedesc}}</td>
  <td>{{$res->applicationcount}}</td>
  <td>{{$res->applicantcount}}</td>
  <td>{{$res->group_applicant_count}}</td>

</tr>

@endforeach
