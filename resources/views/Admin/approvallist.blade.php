<?php $i=1;?>
      @foreach ($usersonline as $users)

   <tr>
     @if($statusofadvocatechange == 1 || $statusofadvocatechange == 2)
      <td><input type="checkbox" name="ids[]" id="ids" class="checkBoxClass" value="{{$users->id}}"/></td>
     @endif
     <td class="hiding23">{{ $i++}}</td>
     <td>{{ $users->name}}</td>
     <td>{{ $users->mobile}}</td>
     <td>{{ $users->advocateregno}}</td>
     <td>{{ $users->advocatename}}</td>
     <td>{{ $users->advocateaddress}}</td>
     <td>{{ $users->distname}}</td>
     <td>{{ $users->talukname}}</td>
     <td>{{ $users->pincode}} </td>
     @if($statusofadvocatechange == 2)
     <td>{{ $users->aadvocatename}}</td>
     <td>{{ $users->aadvocateaddress}}</td>
     <td>{{ $users->adistname}}</td>
     <td>{{ $users->atalukname}}</td>
    <td>{{ $users->apincode}}</td>
    @endif


@endforeach 
