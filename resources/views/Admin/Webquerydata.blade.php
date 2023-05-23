<?php $i=1; ?>
        @foreach($result as $res)
        <tr>
          <td>{{$i++}}</td>
          <td>{{$res->querytypedescription}}</td>
          <td>{{$res->querycontent}}</td>
          <td> @if($res->statuscode == 1)
                 Pending
            @else
                Closed
            @endif
          </td>
          <td>{{$res->mobileno}}</td>
          <td>{{date("d-m-Y", strtotime($res->enteron))}}</td>
          <td>@if($res->repliedon)
              {{date("d-m-Y", strtotime($res->repliedon))}}
           @endif</td>
          <td>{{$res->replycontent}}</td>
          <td>{{$res->usersecname}}
             </td>
          <td>@if($res->forwardedon)
              {{date("d-m-Y", strtotime($res->forwardedon))}}
          @endif</td>
          <td>{{$res->forwardedto}}</td>
          <td>@if($res->acknowledgeon)
                    {{date("d-m-Y", strtotime($res->acknowledgeon))}}
                @endif</td>
          <td>{{$res->userid}}</td>
        </tr>
        @endforeach
