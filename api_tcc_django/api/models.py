# This is an auto-generated Django model module.
# You'll have to do the following manually to clean this up:
#   * Rearrange models' order
#   * Make sure each model has one field with primary_key=True
#   * Make sure each ForeignKey and OneToOneField has `on_delete` set to the desired behavior
#   * Remove `managed = False` lines if you wish to allow Django to create, modify, and delete the table
# Feel free to rename the models, but don't rename db_table values or field names.
from django.db import models


class AuthGroup(models.Model):
    name = models.CharField(unique=True, max_length=150)

    class Meta:
        managed = False
        db_table = 'auth_group'


class AuthGroupPermissions(models.Model):
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)
    permission = models.ForeignKey('AuthPermission', models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_group_permissions'
        unique_together = (('group', 'permission'),)


class AuthPermission(models.Model):
    name = models.CharField(max_length=255)
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING)
    codename = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'auth_permission'
        unique_together = (('content_type', 'codename'),)


class AuthUser(models.Model):
    password = models.CharField(max_length=128)
    last_login = models.DateTimeField(blank=True, null=True)
    is_superuser = models.IntegerField()
    username = models.CharField(unique=True, max_length=150)
    first_name = models.CharField(max_length=30)
    last_name = models.CharField(max_length=150)
    email = models.CharField(max_length=254)
    is_staff = models.IntegerField()
    is_active = models.IntegerField()
    date_joined = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'auth_user'


class AuthUserGroups(models.Model):
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    group = models.ForeignKey(AuthGroup, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_groups'
        unique_together = (('user', 'group'),)


class AuthUserUserPermissions(models.Model):
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)
    permission = models.ForeignKey(AuthPermission, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'auth_user_user_permissions'
        unique_together = (('user', 'permission'),)


class Balada(models.Model):
    id_balada = models.AutoField(primary_key=True)
    desc_balada = models.CharField(max_length=60, blank=True, null=True)
    id_tipo_balada = models.ForeignKey('TipoBalada', models.DO_NOTHING, db_column='id_tipo_balada', blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'balada'


class CoreCliente(models.Model):
    nome = models.CharField(max_length=100)
    endereco = models.ForeignKey('CoreEndereco', models.DO_NOTHING)
    idade = models.IntegerField()

    class Meta:
        managed = False
        db_table = 'core_cliente'


class CoreEndereco(models.Model):
    rua = models.CharField(max_length=100)
    cidade = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'core_endereco'


class CoreTelefone(models.Model):
    telefone = models.IntegerField(unique=True)

    class Meta:
        managed = False
        db_table = 'core_telefone'


class CoreUsuario(models.Model):
    codigo = models.AutoField(primary_key=True)
    nome = models.CharField(max_length=50)
    email = models.CharField(max_length=30, blank=True, null=True)
    senha = models.CharField(max_length=15, blank=True, null=True)
    admin = models.IntegerField(blank=True, null=True)

    class Meta:
        managed = False
        db_table = 'core_usuario'


class DjangoAdminLog(models.Model):
    action_time = models.DateTimeField()
    object_id = models.TextField(blank=True, null=True)
    object_repr = models.CharField(max_length=200)
    action_flag = models.PositiveSmallIntegerField()
    change_message = models.TextField()
    content_type = models.ForeignKey('DjangoContentType', models.DO_NOTHING, blank=True, null=True)
    user = models.ForeignKey(AuthUser, models.DO_NOTHING)

    class Meta:
        managed = False
        db_table = 'django_admin_log'


class DjangoContentType(models.Model):
    app_label = models.CharField(max_length=100)
    model = models.CharField(max_length=100)

    class Meta:
        managed = False
        db_table = 'django_content_type'
        unique_together = (('app_label', 'model'),)


class DjangoMigrations(models.Model):
    app = models.CharField(max_length=255)
    name = models.CharField(max_length=255)
    applied = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_migrations'


class DjangoSession(models.Model):
    session_key = models.CharField(primary_key=True, max_length=40)
    session_data = models.TextField()
    expire_date = models.DateTimeField()

    class Meta:
        managed = False
        db_table = 'django_session'


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
