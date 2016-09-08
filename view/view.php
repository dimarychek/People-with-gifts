<?php

class View1
{
    public function views()
    {
        global $statusr;
        global $ip;
        global $user_ip;
        global $bal;
        $user_ip = $_SERVER["REMOTE_ADDR"];
        if ($ip != $user_ip) {
            $forms = "<div class='giftw'>
                        <div class='gift_block'>
                        <a href='#openModal' class='modal1'></a>
                      </div>";
            $forms .= "
                  <div id='openModal' class='modalDialog'>
                    <div>
                        <h2>АКЦИЯ!</h2>
                        <a href='#close' title='Закрыть' class='closer'>X</a>
                        <p>Заходите на наш сайт каждый день на протяжени 100 дней и Вы получите подарок!</p>
                    </div>
                  </div>
                  </div>";
        } else {

            if ($bal == 100 && $statusr == 'На рассмотрение') {
                $forms = "
                        <div class='giftw'>
                        <script>$(document).ready(function() {
                        $('.gift_count').hide()
                    });</script>";
                $forms .= "<div class='gift_block2'>
                        <a href='#openModal3' class='modal3'></a>
                      </div>
                      ";
                $forms .= "
                  <div id='openModal3' class='modalDialog'>
                    <div>
                        <h2>АКЦИЯ!</h2>
                        <a href='#' title='Закрыть' class='closer'>X</a>
                        <p>Вы уже отправили запрос на подарок. В ближайшее время мы свяжемся с Вами!</p>
                    </div>
                  </div>
                  </div>";
            } elseif ($bal == 100) {
                $forms = "<div class='giftw'>
                        <script>$(document).ready(function() {
                        $('.gift_count').hide()
                    });</script>";
                $forms .= "<div class='gift_block1'>
                        <a href='#openModal1' class='modal2'></a>
                      </div>";
                $forms .= "<script>
                    $(document).ready(function(){
                        $('#openModal1').show()
                        $('.modal2').click(function() {
                        $('#openModal1').show()
                        });
                        $('.closer').click(function() {
                        $('#openModal1').hide();
                        });
                    });
                  </script>
                  <div id='openModal1' class='modalDialog'>
                    <div>
                        <h2>АКЦИЯ!</h2>
                        <a href='#' title='Закрыть' class='closer'>X</a>
                        <p>Вы посещяли наш сайт 100 дней подряд и за это мы дарим Вам подарок!</p>
                        <form class='mail' method='POST' onsubmit='return false;'>
                        <input id='em' class='email' type='email' name= 'email' placeholder='Ваш E-mail'></input>
                        <button class='emailb' type= 'button' >Отправить</button>
                        </form>
                    </div>
                  </div>
                  </div>";
            }
            if($bal < 10) {
                echo "<script>
                        $(document).ready(function() {
                        $('.count_mark').addClass('line_height')
                        })
                      </script>";
            }
            $forms .= " <div class='giftw'><div class='gift_count'>
                            <div class='count_mark'>" . $bal . "</div>
                    <a href='#openModal' class='modal3'></a>
                </div>
                <div id='openModal' class='modalDialog'>
                    <div>
                        <h2>АКЦИЯ!</h2>
                        <a href='#' title='Закрыть' class='closer'>X</a>
                        <p>Продолжайте заходить в том же духе! Вы зашли на сайт " . $bal . "/100 раз.</p>
                    </div>
                </div></div>";
        }
        return $forms;
    }
}