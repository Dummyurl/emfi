@extends('layout')

@section('content')

<section class="top_section top_bg economics_bg">
    <div class="container">
        <input type="hidden" id="main_country_id" value="{{ $countryObj->id }}" />
        
        <div class="title_belt">
            <div class="row">
                <div class="col-md-6">
                  <h2>{{ $countryObj->title }}</h2>
                  <span>{{ date('F d, Y') }}</span>
                </div>
                <div class="col-md-6 select_r">
                    <select id="country-combo">
                        @foreach($countries as $cnt)
                            <option {{ $cnt->id == $countryObj->id ? 'selected="selected"':'' }} value="{{ $cnt->country_code }}">{{ $cnt->title }}</option>
                        @endforeach
                    </select>                    
                </div>
            </div>
        </div>
        
        <div class="row">
            @if(!empty($market_boxes))
                @foreach($market_boxes as $row)
                    @if(trim(strtolower($row['market_name'])) != 'credit')
                    <div data-id="{{ $row['id'] }}" class="col-lg-3 col-md-3 col-sm-6 four_block market-action" style="cursor: pointer;" title="View Graph">
                        <div class="inner_blue_box">
                            <h3>{{ $row['market_name'] or '' }}</h3>
                            <span class="value">
                                {{ number_format($row['last_price'],2)  }}
                            </span>
                            <div class="botm clearfix">
                                <div class="arrow"> 
                                    <i class="up">
                                        @if($row['net_change'] > 0)
                                            <img src="{{ asset('themes/frontend/images/white-arrow-up.png') }}" alt="" />
                                        @else
                                            <img src="{{ asset('themes/frontend/images/white-arrow-down.png') }}" alt="" />
                                        @endif                                        
                                    </i> 
                                </div>
                                <div class="value_num">
                                    <p>
                                        {{ $row['net_change'] > 0 ? "+":""}}{{ number_format($row['net_change'],2)  }}
                                    </p>
                                    <p>
                                        {{ $row['percentage_change'] > 0 ? "+":""}}{{ number_format($row['percentage_change'],2)  }}%
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div> 
                    @endif
                @endforeach
            @endif                
        </div>
    </div>
</section>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2 class="market-chart-title">Equities</h2>
            <span>Historical Chart</span> 
        </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-4">
                            <select id="period-month">
                                <option value="">Period</option>
                                @for($i=1;$i<=12;$i++)
                                <option {!! $i == 1 ? 'selected="selected"':'' !!} value="{{ $i }}">{{ $i }} Month</option>
                                @endfor
                            </select>
                        </div>

                        <?php /*
                        <div class="col-md-4">
                            <select id="price-dropdown" style="display: none;">
                                <option value="1">Price</option>
                                <option value="2">YLD YTM MID</option>
                                <option value="3">Z SPRD MID</option>
                            </select>
                        </div>
                        */ ?>

                        <div class="col-md-4 pull-right">
                            <select id="benchmark-dropdown">
                                <option value="">Add Benchmark</option>
                            </select>
                        </div>                        
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<section class="chart_table grey_bg">
    <div class="container">
        <div class="title">
            <h2>Table Chart</h2>
            <span>Chart</span>
        </div>
    </div>
    <div class="container">
        <div class="table_wrapper">
            <table class="table">
                <thead>
                    <tr>
                        <th>Security</th>
                        <th>Bid</th>
                        <th>Ask</th>
                        <th>Yield</th>
                        <th>Spread</th>
                        <th>Change</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($bond_data as $row)
                    <tr>
                        <td>
                            <a class="generate-bond-chart" href="javascript:void(0);" data-id="{{ $row['id'] }}" title="View Graph">
                                {{ $row['security_name'] }}
                            </a>
                        </td>
                        <td>
                            {{ number_format($row['bid_price'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['ask_price'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['yld_ytm_mid'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['z_sprd_mid'],2) }}
                        </td>
                        <td>
                            {{ number_format($row['net_change'],2) }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
<section class="equities">
    <div class="container">
        <div class="title">
            <h2 class="market-chart-title-2"></h2>
            <span>Historical Chart</span> </div>
    </div>
    <div class="container chart_section">
        <div class="row">
            <div class="col-lg-12">
                <div id="curve_chart2" style="width: 100%; height: 480px"> </div>
                <div class="chart_dropdown clearfix">
                    <form>
                        <div class="col-md-4">
                            <select id="period-month-2">
                                <option value="">Period</option>
                                @for($i=1;$i<=12;$i++)
                                <option {!! $i == 1 ? 'selected="selected"':'' !!} value="{{ $i }}">{{ $i }} Month</option>
                                @endfor
                            </select>                            
                        </div>
                        <div class="col-md-4">
                            <select id="price-dropdown-2">
                                <option value="1">Price</option>
                                <option value="2">YIELD</option>
                                <option value="3">SPREAD</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select id="benchmark-dropdown-2">
                                <option value="">Add Benchmark</option>
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@include('includes.twitter')

@stop
@section('scripts')
<script src="{{ asset('themes/frontend/js/economics.js') }}"></script>
@stop
