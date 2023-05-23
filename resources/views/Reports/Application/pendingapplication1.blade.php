<?php $i=1; ?>
@foreach($result as $res)
            <tr>
                <td>{{$i++}}</td>
              <td>{{$res->applicationyear}}</td>
              <td>{{$res->appltypedesc}}</td>
              
              <td>
              <a href="pendingapplicationReport2?id={{$res->applicationyear}}&shortname={{$res->shortname}}&appltypedesc={{$res->appltypedesc}}&registerdate={{$registerdate}}">
                {{$res->applicationcount}}
              </a>
              </td>
               <td>{{$res->applicantcount}}</td>
               <td>{{$res->group_applicant_count}}</td>

            </tr>
@endforeach
