{{--右カラム--}}
<div class="col-md-2">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">月別アーカイブ</h3>
        </div>
        <div class="panel-body">
            <ul class="monthly_archive">
                @forelse($month_list as $value)
                    <li>
                        <a href="{{ route('front_index', ['year' => $value->year, 'month' => $value->month]) }}">
                            {{ $value->year_month }}
                        </a>
                    </li>
                @empty
                    <p>記事がありません</p>
                @endforelse
            </ul>
        </div>
    </div>
</div>
