# from django.db import models
import requests
import json
from bs4 import BeautifulSoup as scrap

class busca():
    retorno = list()
    login = ""
    senha = ""
    aluno = True
    def __init__(self, login, senha, aluno):
        self.retorno = list()
        self.login = login
        self.senha = senha
        self.aluno = aluno
        return None

    def loginNSA(self, login, senha, aluno):
        if len(login) > 0 and len(senha) > 0:
            headers = {
                'User-Agent': 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_11_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/66.0.3163.100 Safari/537.36'
            }

            try:
                r = requests.get("https://nsa.cps.sp.gov.br/", headers=headers)
            except:
                r = requests.get("https://nsa.cps.sp.gov.br/", headers=headers)
            
            return str(self.metodoSoup(content=r.content, site="https://nsa.cps.sp.gov.br/"))
        else:
            return False
    
    def metodoSoup(self, content, site):
        try:
            html = scrap(content, 'html.parser')
        except:
            html = scrap(content, 'html.parser')

        return html.find(class_='fundologin')