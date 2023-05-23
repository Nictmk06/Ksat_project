@extends('layout.mainlayout')
@section('content')


<div class="content-wrapper">

  <style type="text/css">
  .pager{
  background-color: #337ab7;
  color: #fff;
  }
  .do-scroll{
  width: 100%;
  height: 100px;
  overflow-y: scroll;
  }
  .btnSearch,
  .btnClear{
  display: inline-block;
  vertical-align: top;
  }
  </style>

  <?php error_reporting(0);
  ini_set('display_errors', 0);

  ?>

@include('flash-message')

<div class="container">
<form>
<p>&nbsp;</p>
<h1 style="text-align: center;"><strong><span style="text-decoration: underline;"><em>Circulars</em></span></strong></h1>
<p>&nbsp;</p>
<table style="height: 63px; width: 898px; margin-left: auto; margin-right: auto;" border="1">
<tbody>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;"><strong>Sr.No</strong></td>
<td style="width: 687.975px; height: 13px; text-align: center;"><strong>Circular</strong></td>
<td style="width: 153.025px; height: 13px; text-align: center;"><strong>Link</strong></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">1</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-16-12-2019</td>
<td style="width: 153.025px; height: 13px; text-align: center;">&nbsp;<a href="pdf/circular/1. Circular-16-12-2019.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">2</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-31.01.2020</td>
<td style="width: 153.025px; height: 13px; text-align: center;">&nbsp;<a href="pdf/circular/2. Circular-31.01.2020.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">3</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-15.02.2020</td>
<td style="width: 153.025px; height: 13px; text-align: center;"><a href="pdf/circular/3. Circular-15.02.2020.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">4</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-29.05.2020</td>
<td style="width: 153.025px; height: 13px; text-align: center;"><a href="pdf/circular/4. Circular-29.05.2020.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">5</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-11.09.2020</td>
<td style="width: 153.025px; height: 13px; text-align: center;"><a href="pdf/circular/5. Circular-11.09.2020.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">6</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-23.03.2021</td>
<td style="width: 153.025px; height: 13px; text-align: center;"><a href="pdf/circular/6. Circular-23.03.2021.pdf">View</a></td>
</tr>
<tr style="height: 13px;">
<td style="width: 33px; height: 13px;">7</td>
<td style="width: 687.975px; height: 13px; text-align: center;">Circular-13.08.2021</td>
<td style="width: 153.025px; height: 13px; text-align: center;"><a href="pdf/circular/7. Circular-13.08.2021.pdf">View</a></td>
</tr>
<tr style="height: 13.4px;">
<td style="width: 33px; height: 13.4px;">8</td>
<td style="width: 687.975px; height: 13.4px; text-align: center;">Circular-16.12.2021</td>
<td style="width: 153.025px; height: 13.4px; text-align: center;"><a href="pdf/circular/8. Circular-16.12.2021.pdf">View</a></td>
</tr>
<tr style="height: 13.4px;">
<td style="width: 33px; height: 13.4px;">9</td>
<td style="width: 687.975px; height: 13.4px; text-align: center;">Circular-31.05.2022</td>
<td style="width: 153.025px; height: 13.4px; text-align: center;"><a href="pdf/circular/31.05.2022.pdf">View</a></td>
</tr>

<tr style="height: 13.4px;">
<td style="width: 33px; height: 13.4px;">10</td>
<td style="width: 687.975px; height: 13.4px; text-align: center;">Circular-25.06.2022</td>
<td style="width: 153.025px; height: 13.4px; text-align: center;"><a href="pdf/circular/Circular-25.06.2022.pdf">View</a></td>

</tr>

<tr style="height: 13.4px;">
<td style="width: 33px; height: 13.4px;">11</td>
<td style="width: 687.975px; height: 13.4px; text-align: center;">Circular-19.07.2022</td>
<td style="width: 153.025px; height: 13.4px; text-align: center;"><a href="pdf/circular/Circular-19.07.2022.pdf">View</a></td>

</tr>



</tbody>
</table>
</form>
</div>

 @endsection
