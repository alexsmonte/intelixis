<table style="border-bottom: 1px solid #000000; text-align: left; width: 100%;">
    <tr>
        <td style="background-color: #ffffff; border: 0; text-align: left;">
            <?php if (!empty($estabelecimento->logo)): ?>
                <img src="<?= WWW_ROOT . 'img/' . $estabelecimento->logo ?>" style="height:100px;">
            <?php endif; ?>
        </td>

        <td style="border: 0; text-align: right;">
            <h2 style="text-align: center;"><?= $estabelecimento->nome_fantasia; ?></h1>
        </td>
    </tr>
</table>
