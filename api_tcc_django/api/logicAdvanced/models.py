# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#   * Rearrange models' order
#   * Make sure each model has one field with primary_key=True
#   * Make sure each ForeignKey and OneToOneField has `on_delete` set to the desired behavior
#   * Remove `managed = False` lines if you wish to allow Django to create, modify, and delete the table
# Feel free to rename the models, but don't rename db_table values or field names.
from django.db import models


class Balada(models.Model):
    id_balada = models.AutoField(primary_key=True)
    desc_balada = models.CharField(max_length=60, blank=True, null=True)
    id_tipo_balada = models.ForeignKey('TipoBalada', models.DO_NOTHING, db_column='id_tipo_balada', blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'balada'


class FeedPost(models.Model):
    id_post = models.AutoField(primary_key=True)
    titulo_post = models.CharField(max_length=100, blank=True, null=True)
    descricao_post = models.CharField(max_length=250, blank=True, null=True)
    endereco_post = models.CharField(max_length=70, blank=True, null=True)
    data_post = models.DateField(blank=True, null=True)
    link_fb = models.CharField(max_length=250, blank=True, null=True)
    link_instagram = models.CharField(max_length=250, blank=True, null=True)
    link_twitter = models.CharField(max_length=250, blank=True, null=True)
    link_wpp = models.CharField(max_length=250, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'feed_post'


class Mensagens(models.Model):
    descricao = models.CharField(max_length=50)
    autor = models.CharField(max_length=30)
    id_post = models.IntegerField()
    id_pergunta = models.IntegerField(blank=True, null=True)
    data_postagem = models.DateTimeField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'mensagens'


class Midia(models.Model):
    id_midia = models.AutoField(primary_key=True)
    midia = models.TextField(blank=True, null=True)
    id_post_midia = models.ForeignKey(FeedPost, models.DO_NOTHING, db_column='id_post_midia', blank=True, null=True)
    tipo_midia = models.CharField(max_length=15, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'midia'


class TipoBalada(models.Model):
    id_tipo = models.IntegerField(primary_key=True)
    desc_tipo = models.CharField(max_length=50, blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'tipo_balada'


class Usuario(models.Model):
    codigo = models.AutoField(primary_key=True)
    nome = models.CharField(max_length=50)
    email = models.CharField(max_length=30, blank=True, null=True)
    senha = models.CharField(max_length=15, blank=True, null=True)
    admin = models.IntegerField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'usuario'
