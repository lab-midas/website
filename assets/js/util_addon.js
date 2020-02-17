function getMail(user, sitenr=1, addons=0) {
  if(sitenr==1)
  {
    site = 'med.uni-tuebingen.de';
  } else if (sitenr == 2) {
    site = 'iss.uni-stuttgart.de';
  } else {
    site = sitenr;
  }
  if(addons == 0)
  {
    html = document.write('<a href=\"mailto:' + user + '@' + site + '\"' + 'class=\"icon solid fa-envelope\" title=\"Email\"><span class=\"label\">Email</span>');
    html += document.write('  <a href=\"mailto:' + user + '@' + site + '\">');
    html += document.write(user + '@' + site + '</a>');
  } else {
    html = document.write('<a href=\"mailto:' + user + '@' + site + '\"' + addons);
  }
  return html;
}
