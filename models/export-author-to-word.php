<?php
    header("Content-type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
    header("Content-Disposition: attachment;Filename=Adam-Nikolic-101/19.doc");
    $string = "<table>
                 <thead>
                     <tr>
                        <th><b>Adam Nikolic</b> 101/19</th>
                     </tr>
                 </thead>
                 <tbody>
                     <tr>
                         <td>Hi. I'm a web developer from Požega, Serbia. Right now I'm studying <a href='https://www.ict.edu.rs'>Internet Technologies at Information and Communication Technologies College</a> in Belgrade and I'm pursuing career in Web programming.</td>
                    </tr>
                 </tbody>
              </table>";
    echo $string;