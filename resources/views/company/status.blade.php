@if($data->status == 0)
<span class="badge bg-purple">Not Verified</span>
@elseif($data->status ==1)
<span class="badge bg-blue">Awaiting Submission</span>
@elseif($data->status ==2)
<span class="badge bg-cyan">Awaiting Moderation</span>
@elseif($data->status ==3)
<span class="badge bg-pink">Insufficient Data</span>
@elseif($data->status ==4)
<span class="badge bg-red">Rejected</span>
@elseif($data->status ==5)
<span class="badge bg-green">Active</span>
@else
<span class="badge bg-red">Disbaled</span>
@endif