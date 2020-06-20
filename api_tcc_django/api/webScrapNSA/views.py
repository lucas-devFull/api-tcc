# from django.shortcuts import render
from .models import busca
from rest_framework.viewsets import ModelViewSet
from rest_framework.response import Response
import json
from django.http import JsonResponse

class loginNSA(ModelViewSet):
    def create(self, request, *args, **kwargs):
        dados = request.data
        sla = busca(login="teste", senha="teste", aluno=True)
        nome = sla.loginNSA(login="teste", senha="teste", aluno=True)
        return Response({"dados":nome})
