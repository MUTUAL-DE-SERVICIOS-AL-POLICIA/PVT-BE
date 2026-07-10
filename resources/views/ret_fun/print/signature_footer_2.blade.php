@foreach ($qualification_users as $qualification_user)
    <table class="m-t-75">
        <tr>
            <td class="no-border text-center text-base w-100 align-bottom">
                <span class="font-bold">
                    ----------------------------------------------------
                </span>
            </td>
        </tr>
        <tr>
            <td class="no-border text-center text-base w-100">
                <span class="font-bold block">{!! strtoupper($qualification_user->fullName()) !!}</span>
                <div class="text-xs text-center uppercase" style="width: 350px; margin:0 auto; font-weight:100">{!! $qualification_user->position !!}</div>
            </td>
        </tr>
    </table>
@endforeach